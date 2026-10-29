<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\DigitalCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalCardController extends Controller
{
    /**
     * Display a listing of digital business cards.
     */
    public function index(Request $request)
    {
        $query = DigitalCard::with(['client', 'creator', 'nfcTags']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('template')) {
            $query->where(function ($q) use ($request) {
                $q->where('template_id', $request->template)
                    ->orWhere('template', $request->template);
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assigned')) {
            if ($request->assigned === 'assigned') {
                $query->whereNotNull('client_id');
            } elseif ($request->assigned === 'unassigned') {
                $query->whereNull('client_id');
            }
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'oldest' => $query->oldest(),
                'views_high' => $query->orderByDesc('views_count'),
                'views_low' => $query->orderBy('views_count'),
                'name' => $query->orderBy('name'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $cards = $query->paginate(15)->withQueryString();
        $clients = Client::orderBy('name')->get();

        $metrics = [
            'total' => DigitalCard::count(),
            'views' => (int) DigitalCard::sum('views_count'),
            'active' => DigitalCard::where('status', 'active')->count(),
            'inactive' => DigitalCard::where('status', '!=', 'active')->count(),
            'with_nfc' => DigitalCard::has('nfcTags')->count(),
        ];

        return view('admin.digital_cards.index', compact('cards', 'clients', 'metrics'));
    }

    /**
     * Export digital business cards list to CSV format
     */
    public function exportCsv(): Response
    {
        $cards = DigitalCard::with('client')->latest()->get();
        $csv = "Code,Statut,Client,Profil / Lien direct,Scans,Date\n";
        foreach ($cards as $c) {
            $code = $c->slug ?: ('CD-'.str_pad((string) $c->id, 6, '0', STR_PAD_LEFT));
            $dest = $c->public_url;
            $client = $c->client?->name ?? '—';
            $views = $c->views_count ?? 0;
            $csv .= "\"{$code}\",\"{$c->status}\",\"{$client}\",\"{$dest}\",{$views},\"{$c->created_at}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="cartes_digitales_'.date('Ymd_His').'.csv"',
        ]);
    }

    /**
     * Show the form for creating a new digital business card.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();

        // 3 presets: Modern Minimal, Corporate Sleek, Creative Bold
        $presets = [
            'modern' => [
                'name' => 'Modern Minimal',
                'primary_color' => '#4f46e5',
                'background_color' => '#0f172a',
                'font_family' => 'Outfit',
                'border_radius' => '16px',
                'shadow_style' => 'soft',
                'element_spacing' => '14px',
            ],
            'executive' => [
                'name' => 'Corporate Sleek',
                'primary_color' => '#0ea5e9',
                'background_color' => '#0b1329',
                'font_family' => 'Inter',
                'border_radius' => '8px',
                'shadow_style' => 'deep',
                'element_spacing' => '12px',
            ],
            'creative' => [
                'name' => 'Creative Bold',
                'primary_color' => '#ec4899',
                'background_color' => '#18021c',
                'font_family' => 'Space Grotesk',
                'border_radius' => '24px',
                'shadow_style' => 'glow',
                'element_spacing' => '18px',
            ],
        ];

        $defaultTheme = $presets['modern'];

        $defaultBlocks = [
            ['id' => (string) Str::uuid(), 'type' => 'contact', 'icon' => 'bi-telephone-fill', 'label' => 'Phone Call', 'url' => '+212 600 112 233', 'order' => 1],
            ['id' => (string) Str::uuid(), 'type' => 'contact', 'icon' => 'bi-whatsapp', 'label' => 'WhatsApp Chat', 'url' => '+212600112233', 'order' => 2],
            ['id' => (string) Str::uuid(), 'type' => 'contact', 'icon' => 'bi-envelope-fill', 'label' => 'Email Direct', 'url' => 'contact@agency.com', 'order' => 3],
            ['id' => (string) Str::uuid(), 'type' => 'custom_link', 'icon' => 'bi-globe', 'label' => 'Official Website', 'url' => 'https://agency.com', 'order' => 4],
            ['id' => (string) Str::uuid(), 'type' => 'social', 'icon' => 'bi-linkedin', 'label' => 'LinkedIn Profile', 'url' => 'https://linkedin.com/in/profile', 'order' => 5],
        ];

        return view('admin.digital_cards.create', compact('clients', 'presets', 'defaultTheme', 'defaultBlocks'));
    }

    /**
     * Store a newly created digital business card in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:150',
            'name' => 'nullable|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'template' => 'nullable|string|max:64',
            'template_id' => 'nullable|string|max:64',
            'full_name' => 'nullable|string|max:150',
            'job_title' => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'bio' => 'nullable|string|max:2000',
            'badge_text' => 'nullable|string|max:60',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'theme_config' => 'nullable',
            'content_blocks' => 'nullable',
            'links' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        $title = $validated['title'] ?? ($validated['name'] ?? ($validated['full_name'] ?? 'Digital Business Card'));
        $template = $validated['template_id'] ?? ($validated['template'] ?? 'modern');
        $slug = DigitalCard::generateUniqueSlug();

        // Handle profile photo upload
        $profilePath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePath = $request->file('profile_photo')->store('digital_cards/profiles', 'public');
        } elseif ($request->hasFile('profile_image')) {
            $profilePath = $request->file('profile_image')->store('digital_cards/profiles', 'public');
        }

        // Handle cover photo upload
        $coverPath = null;
        if ($request->hasFile('cover_photo')) {
            $coverPath = $request->file('cover_photo')->store('digital_cards/covers', 'public');
        } elseif ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('digital_cards/covers', 'public');
        }

        // Process theme_config
        $themeConfig = $request->input('theme_config');
        if (is_string($themeConfig)) {
            $themeConfig = json_decode($themeConfig, true) ?? [];
        } elseif (! is_array($themeConfig)) {
            $themeConfig = [];
        }

        // Ensure theme_config has all expected keys
        $themeConfig = array_merge([
            'primary_color' => $request->input('primary_color', '#4f46e5'),
            'background_color' => $request->input('background_color', '#0f172a'),
            'font_family' => $request->input('font_family', 'Outfit'),
            'border_radius' => $request->input('border_radius', '16px'),
            'shadow_style' => $request->input('shadow_style', 'soft'),
            'element_spacing' => $request->input('element_spacing', '14px'),
        ], $themeConfig);

        // Process content_blocks and links
        $contentBlocks = $request->input('content_blocks');
        if (is_string($contentBlocks)) {
            $contentBlocks = json_decode($contentBlocks, true) ?? [];
        } elseif (! is_array($contentBlocks)) {
            $contentBlocks = [];
        }

        // If links array was submitted directly (legacy tests), normalize into content_blocks
        $legacyLinks = $request->input('links');
        if (empty($contentBlocks) && ! empty($legacyLinks) && is_array($legacyLinks)) {
            $order = 1;
            foreach ($legacyLinks as $item) {
                $contentBlocks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => $item['type'] ?? 'custom_link',
                    'icon' => $item['icon'] ?? 'bi-link-45deg',
                    'label' => $item['title'] ?? ($item['label'] ?? 'Link'),
                    'url' => $item['value'] ?? ($item['url'] ?? ''),
                    'order' => $order++,
                ];
            }
        }

        // Create unified links array for backward compatibility
        $cleanLinks = [];
        foreach ($contentBlocks as $block) {
            $cleanLinks[] = [
                'id' => $block['id'] ?? (string) Str::uuid(),
                'type' => $block['type'] ?? 'custom',
                'title' => $block['label'] ?? 'Link',
                'label' => $block['label'] ?? 'Link',
                'value' => $block['url'] ?? '',
                'url' => $block['url'] ?? '',
                'icon' => $block['icon'] ?? 'bi-link-45deg',
                'order' => $block['order'] ?? 0,
            ];
        }

        $card = DigitalCard::create([
            'client_id' => $validated['client_id'] ?? null,
            'user_id' => auth('web')->id(),
            'created_by' => auth('web')->id(),
            'title' => $title,
            'name' => $title,
            'slug' => $slug,
            'template_id' => $template,
            'template' => $template,
            'full_name' => $validated['full_name'] ?? $title,
            'job_title' => $validated['job_title'] ?? null,
            'company' => $validated['company'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'profile_photo_path' => $profilePath,
            'profile_image' => $profilePath,
            'cover_photo_path' => $coverPath,
            'cover_image' => $coverPath,
            'theme_config' => $themeConfig,
            'style_settings' => $themeConfig,
            'content_blocks' => $contentBlocks,
            'links' => $cleanLinks,
            'status' => $validated['status'],
        ]);

        ActivityLog::log(
            action: 'digital_card_created',
            resourceType: 'DigitalCard',
            resourceId: $card->id,
            details: ['name' => $card->title, 'slug' => $card->slug, 'template' => $card->template_id]
        );

        return redirect()->route('admin.digital-cards.index')
            ->with('success', "Digital Business Card '{$card->title}' created successfully! Live at /c/{$card->slug}");
    }

    /**
     * Display the specified digital business card details.
     */
    public function show(DigitalCard $digitalCard)
    {
        $digitalCard->load(['client', 'creator', 'nfcTags']);

        return view('admin.digital_cards.show', compact('digitalCard'));
    }

    /**
     * Show the form for editing the digital business card.
     */
    public function edit(DigitalCard $digitalCard)
    {
        $digitalCard->load('client');
        $clients = Client::where('status', 'active')->orderBy('name')->get();

        $presets = [
            'modern' => [
                'name' => 'Modern Minimal',
                'primary_color' => '#4f46e5',
                'background_color' => '#0f172a',
                'font_family' => 'Outfit',
                'border_radius' => '16px',
                'shadow_style' => 'soft',
                'element_spacing' => '14px',
            ],
            'executive' => [
                'name' => 'Corporate Sleek',
                'primary_color' => '#0ea5e9',
                'background_color' => '#0b1329',
                'font_family' => 'Inter',
                'border_radius' => '8px',
                'shadow_style' => 'deep',
                'element_spacing' => '12px',
            ],
            'creative' => [
                'name' => 'Creative Bold',
                'primary_color' => '#ec4899',
                'background_color' => '#18021c',
                'font_family' => 'Space Grotesk',
                'border_radius' => '24px',
                'shadow_style' => 'glow',
                'element_spacing' => '18px',
            ],
        ];

        $currentTheme = array_merge(
            $presets[$digitalCard->template_id ?? 'modern'] ?? $presets['modern'],
            $digitalCard->theme_config ?? ($digitalCard->style_settings ?? [])
        );

        $currentBlocks = $digitalCard->content_blocks ?? [];
        if (empty($currentBlocks) && ! empty($digitalCard->links)) {
            $order = 1;
            foreach ($digitalCard->links as $link) {
                $currentBlocks[] = [
                    'id' => $link['id'] ?? (string) Str::uuid(),
                    'type' => $link['type'] ?? 'custom_link',
                    'icon' => $link['icon'] ?? 'bi-link-45deg',
                    'label' => $link['title'] ?? ($link['label'] ?? 'Link'),
                    'url' => $link['value'] ?? ($link['url'] ?? ''),
                    'order' => $order++,
                ];
            }
        }

        return view('admin.digital_cards.edit', compact('digitalCard', 'clients', 'presets', 'currentTheme', 'currentBlocks'));
    }

    /**
     * Update the specified digital business card in storage.
     */
    public function update(Request $request, DigitalCard $digitalCard)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:150',
            'name' => 'nullable|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'template' => 'nullable|string|max:64',
            'template_id' => 'nullable|string|max:64',
            'full_name' => 'nullable|string|max:150',
            'job_title' => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'bio' => 'nullable|string|max:2000',
            'badge_text' => 'nullable|string|max:60',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'theme_config' => 'nullable',
            'content_blocks' => 'nullable',
            'links' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        $title = $validated['title'] ?? ($validated['name'] ?? ($validated['full_name'] ?? $digitalCard->title));
        $template = $validated['template_id'] ?? ($validated['template'] ?? $digitalCard->template_id);

        $profilePath = $digitalCard->profile_photo_path ?: $digitalCard->profile_image;
        if ($request->hasFile('profile_photo')) {
            if ($profilePath && Storage::disk('public')->exists($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }
            $profilePath = $request->file('profile_photo')->store('digital_cards/profiles', 'public');
        } elseif ($request->hasFile('profile_image')) {
            if ($profilePath && Storage::disk('public')->exists($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }
            $profilePath = $request->file('profile_image')->store('digital_cards/profiles', 'public');
        }

        $coverPath = $digitalCard->cover_photo_path ?: $digitalCard->cover_image;
        if ($request->hasFile('cover_photo')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_photo')->store('digital_cards/covers', 'public');
        } elseif ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('digital_cards/covers', 'public');
        }

        $themeConfig = $request->input('theme_config');
        if (is_string($themeConfig)) {
            $themeConfig = json_decode($themeConfig, true) ?? [];
        } elseif (! is_array($themeConfig)) {
            $themeConfig = [];
        }

        $themeConfig = array_merge(
            $digitalCard->theme_config ?? [],
            [
                'primary_color' => $request->input('primary_color', $digitalCard->theme_config['primary_color'] ?? '#4f46e5'),
                'background_color' => $request->input('background_color', $digitalCard->theme_config['background_color'] ?? '#0f172a'),
                'font_family' => $request->input('font_family', $digitalCard->theme_config['font_family'] ?? 'Outfit'),
                'border_radius' => $request->input('border_radius', $digitalCard->theme_config['border_radius'] ?? '16px'),
                'shadow_style' => $request->input('shadow_style', $digitalCard->theme_config['shadow_style'] ?? 'soft'),
                'element_spacing' => $request->input('element_spacing', $digitalCard->theme_config['element_spacing'] ?? '14px'),
            ],
            $themeConfig
        );

        $contentBlocks = $request->input('content_blocks');
        if (is_string($contentBlocks)) {
            $contentBlocks = json_decode($contentBlocks, true) ?? [];
        } elseif (! is_array($contentBlocks)) {
            $contentBlocks = [];
        }

        $legacyLinks = $request->input('links');
        if (empty($contentBlocks) && ! empty($legacyLinks) && is_array($legacyLinks)) {
            $order = 1;
            foreach ($legacyLinks as $item) {
                $contentBlocks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => $item['type'] ?? 'custom_link',
                    'icon' => $item['icon'] ?? 'bi-link-45deg',
                    'label' => $item['title'] ?? ($item['label'] ?? 'Link'),
                    'url' => $item['value'] ?? ($item['url'] ?? ''),
                    'order' => $order++,
                ];
            }
        }

        $cleanLinks = [];
        foreach ($contentBlocks as $block) {
            $cleanLinks[] = [
                'id' => $block['id'] ?? (string) Str::uuid(),
                'type' => $block['type'] ?? 'custom',
                'title' => $block['label'] ?? 'Link',
                'label' => $block['label'] ?? 'Link',
                'value' => $block['url'] ?? '',
                'url' => $block['url'] ?? '',
                'icon' => $block['icon'] ?? 'bi-link-45deg',
                'order' => $block['order'] ?? 0,
            ];
        }

        $digitalCard->update([
            'client_id' => $validated['client_id'] ?? $digitalCard->client_id,
            'title' => $title,
            'name' => $title,
            'template_id' => $template,
            'template' => $template,
            'full_name' => $validated['full_name'] ?? $digitalCard->full_name,
            'job_title' => $validated['job_title'] ?? $digitalCard->job_title,
            'company' => $validated['company'] ?? $digitalCard->company,
            'bio' => $validated['bio'] ?? $digitalCard->bio,
            'badge_text' => $validated['badge_text'] ?? $digitalCard->badge_text,
            'profile_photo_path' => $profilePath,
            'profile_image' => $profilePath,
            'cover_photo_path' => $coverPath,
            'cover_image' => $coverPath,
            'theme_config' => $themeConfig,
            'style_settings' => $themeConfig,
            'content_blocks' => $contentBlocks,
            'links' => $cleanLinks,
            'status' => $validated['status'],
        ]);

        ActivityLog::log(
            action: 'digital_card_updated',
            resourceType: 'DigitalCard',
            resourceId: $digitalCard->id,
            details: ['name' => $digitalCard->title]
        );

        return redirect()->route('admin.digital-cards.index')
            ->with('success', "Digital Business Card '{$digitalCard->title}' updated successfully!");
    }

    /**
     * Remove the specified digital business card from storage.
     */
    public function destroy(DigitalCard $digitalCard)
    {
        $name = $digitalCard->title;
        $digitalCard->delete();

        ActivityLog::log(
            action: 'digital_card_deleted',
            resourceType: 'DigitalCard',
            resourceId: $digitalCard->id,
            details: ['name' => $name]
        );

        return redirect()->route('admin.digital-cards.index')
            ->with('success', "Digital Business Card '{$name}' removed.");
    }
}
