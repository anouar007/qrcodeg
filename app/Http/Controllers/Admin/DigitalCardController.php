<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\DigitalCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalCardController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalCard::with(['client', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('company_name', 'like', "%{$search}%")->orWhere('contact_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('template')) {
            $query->where('template', $request->template);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cards = $query->latest()->paginate(15)->withQueryString();
        $clients = Client::orderBy('company_name')->get();

        return view('admin.digital_cards.index', compact('cards', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();

        $defaultStyles = [
            'primary_color' => '#6366f1',
            'background_type' => 'solid',
            'background_color' => '#0f172a',
            'background_gradient' => 'linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%)',
            'card_background' => '#ffffff',
            'text_primary_color' => '#0f172a',
            'text_secondary_color' => '#64748b',
            'font_family' => 'Outfit',
            'border_radius' => '16px',
            'shadow_style' => 'soft',
        ];

        $defaultLayout = [
            'avatar_position' => 'center',
            'avatar_style' => 'circle',
            'buttons_layout' => 'list',
            'show_vcard_button' => true,
        ];

        return view('admin.digital_cards.create', compact('clients', 'defaultStyles', 'defaultLayout'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'template' => 'required|string|in:modern,executive,creative,cyberpunk,glassmorphism',
            'full_name' => 'nullable|string|max:150',
            'job_title' => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'bio' => 'nullable|string|max:1000',
            'badge_text' => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'links' => 'nullable|array',
            'layout_settings' => 'nullable|array',
            'style_settings' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = DigitalCard::generateUniqueSlug();

        $profilePath = null;
        if ($request->hasFile('profile_image')) {
            $profilePath = $request->file('profile_image')->store('digital_cards/profiles', 'public');
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('digital_cards/covers', 'public');
        }

        // Filter and clean links
        $cleanLinks = [];
        if (! empty($validated['links']) && is_array($validated['links'])) {
            foreach ($validated['links'] as $item) {
                if (! empty($item['value'])) {
                    $cleanLinks[] = [
                        'id' => $item['id'] ?? (string) Str::uuid(),
                        'type' => $item['type'] ?? 'custom',
                        'label' => $item['label'] ?? 'Link',
                        'value' => $item['value'],
                        'icon' => $item['icon'] ?? 'bi-link-45deg',
                        'is_active' => true,
                    ];
                }
            }
        }

        $card = DigitalCard::create([
            'client_id' => $validated['client_id'] ?? null,
            'created_by' => auth('web')->id(),
            'slug' => $slug,
            'name' => $validated['name'],
            'template' => $validated['template'],
            'profile_image' => $profilePath,
            'cover_image' => $coverPath,
            'full_name' => $validated['full_name'] ?? null,
            'job_title' => $validated['job_title'] ?? null,
            'company' => $validated['company'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'links' => $cleanLinks,
            'layout_settings' => $request->input('layout_settings', []),
            'style_settings' => $request->input('style_settings', []),
            'status' => $validated['status'],
        ]);

        ActivityLog::log(
            action: 'digital_card_created',
            resourceType: 'DigitalCard',
            resourceId: $card->id,
            details: ['name' => $card->name, 'slug' => $card->slug, 'template' => $card->template]
        );

        return redirect()->route('admin.digital-cards.index')->with('success', "Carte Visite Digitale '{$card->name}' créée avec succès !");
    }

    public function show(DigitalCard $digitalCard)
    {
        $digitalCard->load(['client', 'creator', 'nfcTags']);

        return view('admin.digital_cards.show', compact('digitalCard'));
    }

    public function edit(DigitalCard $digitalCard)
    {
        $digitalCard->load('client');
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.digital_cards.edit', compact('digitalCard', 'clients'));
    }

    public function update(Request $request, DigitalCard $digitalCard)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'template' => 'required|string|in:modern,executive,creative,cyberpunk,glassmorphism',
            'full_name' => 'nullable|string|max:150',
            'job_title' => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'bio' => 'nullable|string|max:1000',
            'badge_text' => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'links' => 'nullable|array',
            'layout_settings' => 'nullable|array',
            'style_settings' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $profilePath = $digitalCard->profile_image;
        if ($request->hasFile('profile_image')) {
            if ($profilePath && Storage::disk('public')->exists($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }
            $profilePath = $request->file('profile_image')->store('digital_cards/profiles', 'public');
        }

        $coverPath = $digitalCard->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('digital_cards/covers', 'public');
        }

        $cleanLinks = [];
        if (! empty($validated['links']) && is_array($validated['links'])) {
            foreach ($validated['links'] as $item) {
                if (! empty($item['value'])) {
                    $cleanLinks[] = [
                        'id' => $item['id'] ?? (string) Str::uuid(),
                        'type' => $item['type'] ?? 'custom',
                        'label' => $item['label'] ?? 'Link',
                        'value' => $item['value'],
                        'icon' => $item['icon'] ?? 'bi-link-45deg',
                        'is_active' => true,
                    ];
                }
            }
        }

        $digitalCard->update([
            'client_id' => $validated['client_id'] ?? null,
            'name' => $validated['name'],
            'template' => $validated['template'],
            'profile_image' => $profilePath,
            'cover_image' => $coverPath,
            'full_name' => $validated['full_name'] ?? null,
            'job_title' => $validated['job_title'] ?? null,
            'company' => $validated['company'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'links' => $cleanLinks,
            'layout_settings' => $request->input('layout_settings', $digitalCard->layout_settings),
            'style_settings' => $request->input('style_settings', $digitalCard->style_settings),
            'status' => $validated['status'],
        ]);

        ActivityLog::log(
            action: 'digital_card_updated',
            resourceType: 'DigitalCard',
            resourceId: $digitalCard->id,
            details: ['name' => $digitalCard->name]
        );

        return redirect()->route('admin.digital-cards.index')->with('success', 'Carte Visite Digitale mise à jour avec succès !');
    }

    public function destroy(DigitalCard $digitalCard)
    {
        $name = $digitalCard->name;
        $digitalCard->delete();

        ActivityLog::log(
            action: 'digital_card_deleted',
            resourceType: 'DigitalCard',
            resourceId: $digitalCard->id,
            details: ['name' => $name]
        );

        return redirect()->route('admin.digital-cards.index')->with('success', "Carte Visite Digitale {$name} supprimée.");
    }
}
