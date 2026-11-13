<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DigitalCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientDigitalCardController extends Controller
{
    /**
     * Display a listing of digital business cards and menus owned by the authenticated client.
     */
    public function index(Request $request): View
    {
        $client = auth('client')->user();

        // Strict ownership scoping: only the authenticated client's records
        $query = DigitalCard::where('client_id', $client->id);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $digitalCards = $query->latest()->paginate(15)->withQueryString();

        return view('client.digital_cards.index', compact('client', 'digitalCards'));
    }

    /**
     * Show the edit form for a client's digital business card.
     */
    public function edit(DigitalCard $digitalCard): View
    {
        $this->authorizeClientAccess($digitalCard);

        return view('client.digital_cards.edit', compact('digitalCard'));
    }

    /**
     * Update the client's digital business card details.
     */
    public function update(Request $request, DigitalCard $digitalCard): RedirectResponse
    {
        $this->authorizeClientAccess($digitalCard);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'full_name' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:150',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'linkedin' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Merge contact and social links into content_blocks / links JSON
        $contentBlocks = $digitalCard->content_blocks ?? [];
        $contentBlocks['bio'] = $validated['bio'] ?? ($contentBlocks['bio'] ?? null);

        $contactDetails = [];
        if (! empty($validated['phone'])) {
            $contactDetails[] = ['type' => 'phone', 'label' => 'Téléphone', 'value' => $validated['phone'], 'icon' => 'bi-telephone-fill'];
        }
        if (! empty($validated['email'])) {
            $contactDetails[] = ['type' => 'email', 'label' => 'Email', 'value' => $validated['email'], 'icon' => 'bi-envelope-fill'];
        }
        if (! empty($validated['website'])) {
            $contactDetails[] = ['type' => 'website', 'label' => 'Site Web', 'value' => $validated['website'], 'icon' => 'bi-globe'];
        }
        $contentBlocks['contact_details'] = $contactDetails;

        $socialLinks = [];
        if (! empty($validated['whatsapp'])) {
            $socialLinks[] = ['platform' => 'whatsapp', 'label' => 'WhatsApp', 'url' => 'https://wa.me/'.preg_replace('/[^0-9]/', '', $validated['whatsapp']), 'icon' => 'bi-whatsapp'];
        }
        if (! empty($validated['linkedin'])) {
            $socialLinks[] = ['platform' => 'linkedin', 'label' => 'LinkedIn', 'url' => $validated['linkedin'], 'icon' => 'bi-linkedin'];
        }
        if (! empty($validated['instagram'])) {
            $socialLinks[] = ['platform' => 'instagram', 'label' => 'Instagram', 'url' => $validated['instagram'], 'icon' => 'bi-instagram'];
        }
        $contentBlocks['social_links'] = $socialLinks;

        $digitalCard->update([
            'title' => $validated['title'],
            'name' => $validated['title'],
            'full_name' => $validated['full_name'],
            'job_title' => $validated['job_title'],
            'company' => $validated['company'],
            'bio' => $validated['bio'],
            'status' => $validated['status'],
            'content_blocks' => $contentBlocks,
        ]);

        ActivityLog::log(
            action: 'client_digital_card_updated',
            resourceType: 'DigitalCard',
            resourceId: $digitalCard->id,
            details: ['title' => $digitalCard->title, 'slug' => $digitalCard->slug],
            client: auth('client')->user()
        );

        return redirect()->route('client.digital-cards.index')
            ->with('success', "Carte Digitale '{$digitalCard->title}' mise à jour avec succès !");
    }

    /**
     * Show the interactive restaurant menu & catalogue management view for the card.
     */
    public function menu(DigitalCard $digitalCard): View
    {
        $this->authorizeClientAccess($digitalCard);

        $currentTheme = array_merge([
            'primary_color' => '#5736e9',
            'background_color' => '#0f172a',
            'font_family' => 'Plus Jakarta Sans',
            'border_radius' => '16px',
        ], $digitalCard->theme_config ?? ($digitalCard->style_settings ?? []));

        $currentMenu = array_merge([
            'enabled' => true,
            'title' => 'Notre Menu & Catalogue',
            'button_label' => 'Consulter le Menu',
            'subtitle' => 'Découvrez nos offres et spécialités à jour.',
            'currency' => 'MAD',
            'navigation_mode' => 'vertical',
            'categories' => [],
        ], $digitalCard->menu_config ?? []);

        return view('client.digital_cards.menu', compact('digitalCard', 'currentTheme', 'currentMenu'));
    }

    /**
     * Save updated menu categories, dishes, and prices.
     */
    public function updateMenu(Request $request, DigitalCard $digitalCard): RedirectResponse
    {
        $this->authorizeClientAccess($digitalCard);

        $menuConfig = $request->input('menu_config');
        if (is_string($menuConfig)) {
            $menuConfig = json_decode($menuConfig, true) ?? [];
        } elseif (! is_array($menuConfig)) {
            $menuConfig = [];
        }

        $digitalCard->update([
            'menu_config' => $menuConfig,
        ]);

        ActivityLog::log(
            action: 'client_digital_card_menu_updated',
            resourceType: 'DigitalCard',
            resourceId: $digitalCard->id,
            details: [
                'name' => $digitalCard->title,
                'categories_count' => count($menuConfig['categories'] ?? []),
            ],
            client: auth('client')->user()
        );

        return redirect()->route('client.digital-cards.menu', $digitalCard)
            ->with('success', 'Votre Menu / Catalogue a été enregistré et publié en direct !');
    }

    /**
     * Verify that the requested digital card belongs strictly to the authenticated client.
     */
    protected function authorizeClientAccess(DigitalCard $digitalCard): void
    {
        $client = auth('client')->user();
        if ($digitalCard->client_id !== $client->id) {
            abort(403, 'Accès non autorisé à cette carte digitale.');
        }
    }
}
