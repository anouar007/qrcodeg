<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\RestaurantMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantMenuController extends Controller
{
    /**
     * Display a listing of menus and clients with menus.
     */
    public function index(Request $request): View
    {
        $query = RestaurantMenu::with('client');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', "%{$search}%")
                            ->orWhere('contact_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('brand_type')) {
            $query->where('brand_type', $request->brand_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'views_desc' => $query->orderByDesc('views_count'),
            'title_asc' => $query->orderBy('title'),
            default => $query->latest(),
        };

        $menus = $query->paginate(15)->withQueryString();

        // High-level statistics
        $stats = [
            'total_menus' => RestaurantMenu::count(),
            'active_menus' => RestaurantMenu::where('status', 'active')->count(),
            'total_views' => (int) RestaurantMenu::sum('views_count'),
            'clients_with_menus' => Client::whereHas('restaurantMenus')->count(),
        ];

        // Clients who have at least one menu
        $clientsWithMenus = Client::whereHas('restaurantMenus')
            ->withCount('restaurantMenus')
            ->orderBy('company_name')
            ->get();

        // All active clients for dropdown / assignment
        $allClients = Client::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.menus.index', compact('menus', 'stats', 'clientsWithMenus', 'allClients'));
    }

    /**
     * Show the dedicated interface to create a standalone menu.
     */
    public function create(Request $request): View
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $selectedClientId = $request->input('client_id');

        return view('admin.menus.create', compact('clients', 'selectedClientId'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:150'],
            'brand_name' => ['required', 'string', 'max:150'],
            'brand_type' => ['required', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:15'],
            'description' => ['nullable', 'string', 'max:1000'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'bg_color' => ['nullable', 'string', 'max:20'],
            'item_bg_color' => ['nullable', 'string', 'max:20'],
            'item_text_color' => ['nullable', 'string', 'max:20'],
            'service_bg_color' => ['nullable', 'string', 'max:20'],
            'service_text_color' => ['nullable', 'string', 'max:20'],
            'table_service_enabled' => ['nullable', 'boolean'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'whatsapp_message' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255'],
            'wifi_ssid' => ['nullable', 'string', 'max:100'],
            'wifi_password' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,draft,archived'],
            'nav_style' => ['nullable', 'string', 'in:vertical_scroll,horizontal_scroll,pages,pills,tabs,grid_drawer'],
            'categories_json' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'cover' => ['nullable', 'image', 'max:6144'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'cover_image_url' => ['nullable', 'string', 'max:500'],
        ]);

        $logoUrl = $validated['logo_url'] ?? null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('menus/logos', 'public');
            $logoUrl = Storage::url($path);
        }

        $coverUrl = $validated['cover_image_url'] ?? null;
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('menus/covers', 'public');
            $coverUrl = Storage::url($path);
        }

        $categories = [];
        if (! empty($validated['categories_json'])) {
            $decoded = json_decode($validated['categories_json'], true);
            if (is_array($decoded)) {
                $categories = $this->processCategoriesImages($decoded);
            }
        }

        $slug = RestaurantMenu::generateUniqueSlug($validated['brand_name'].' '.$validated['title']);

        $menu = RestaurantMenu::create([
            'client_id' => $validated['client_id'] ?: null,
            'title' => $validated['title'],
            'slug' => $slug,
            'brand_name' => $validated['brand_name'],
            'brand_type' => $validated['brand_type'],
            'currency' => strtoupper(trim($validated['currency'])),
            'description' => $validated['description'] ?? null,
            'logo_url' => $logoUrl,
            'cover_image' => $coverUrl,
            'theme_color' => $validated['theme_color'] ?? '#5736e9',
            'bg_color' => $validated['bg_color'] ?? '#f8fafc',
            'item_bg_color' => $validated['item_bg_color'] ?? '#ffffff',
            'item_text_color' => $validated['item_text_color'] ?? '#1e293b',
            'service_bg_color' => $validated['service_bg_color'] ?? ($validated['item_bg_color'] ?? '#ffffff'),
            'service_text_color' => $validated['service_text_color'] ?? ($validated['item_text_color'] ?? '#1e293b'),
            'nav_style' => $this->normalizeNavStyle($validated['nav_style'] ?? null),
            'table_service_enabled' => $request->boolean('table_service_enabled', true),
            'categories' => $categories,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'whatsapp_message' => $validated['whatsapp_message'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'website' => $validated['website'] ?? null,
            'wifi_ssid' => $validated['wifi_ssid'] ?? null,
            'wifi_password' => $validated['wifi_password'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth('web')->id(),
                'action' => 'restaurant_menu_create',
                'description' => "Created digital menu '{$menu->title}' for {$menu->brand_name}",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', "Le menu '{$menu->title}' a été créé avec succès.");
    }

    /**
     * Display the specified menu with live preview & table QR code.
     */
    public function show(RestaurantMenu $menu): View
    {
        $menu->load('client');
        $publicUrl = route('menu.public.show', $menu->slug);

        $qrSvg = (string) QrCode::format('svg')
            ->size(280)
            ->margin(1)
            ->color(87, 54, 233)
            ->generate($publicUrl);

        return view('admin.menus.show', compact('menu', 'publicUrl', 'qrSvg'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(RestaurantMenu $menu): View
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.menus.edit', compact('menu', 'clients'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, RestaurantMenu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:150'],
            'brand_name' => ['required', 'string', 'max:150'],
            'brand_type' => ['required', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:15'],
            'description' => ['nullable', 'string', 'max:1000'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'bg_color' => ['nullable', 'string', 'max:20'],
            'item_bg_color' => ['nullable', 'string', 'max:20'],
            'item_text_color' => ['nullable', 'string', 'max:20'],
            'service_bg_color' => ['nullable', 'string', 'max:20'],
            'service_text_color' => ['nullable', 'string', 'max:20'],
            'table_service_enabled' => ['nullable', 'boolean'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'whatsapp_message' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255'],
            'wifi_ssid' => ['nullable', 'string', 'max:100'],
            'wifi_password' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,draft,archived'],
            'nav_style' => ['nullable', 'string', 'in:vertical_scroll,horizontal_scroll,pages,pills,tabs,grid_drawer'],
            'categories_json' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'cover' => ['nullable', 'image', 'max:6144'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'cover_image_url' => ['nullable', 'string', 'max:500'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_cover' => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'client_id' => $validated['client_id'] ?: null,
            'title' => $validated['title'],
            'brand_name' => $validated['brand_name'],
            'brand_type' => $validated['brand_type'],
            'currency' => strtoupper(trim($validated['currency'])),
            'description' => $validated['description'] ?? null,
            'theme_color' => $validated['theme_color'] ?? '#5736e9',
            'bg_color' => $validated['bg_color'] ?? '#f8fafc',
            'item_bg_color' => $validated['item_bg_color'] ?? '#ffffff',
            'item_text_color' => $validated['item_text_color'] ?? '#1e293b',
            'service_bg_color' => $validated['service_bg_color'] ?? ($validated['item_bg_color'] ?? '#ffffff'),
            'service_text_color' => $validated['service_text_color'] ?? ($validated['item_text_color'] ?? '#1e293b'),
            'nav_style' => $this->normalizeNavStyle($validated['nav_style'] ?? null),
            'table_service_enabled' => $request->boolean('table_service_enabled', true),
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'whatsapp_message' => $validated['whatsapp_message'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'website' => $validated['website'] ?? null,
            'wifi_ssid' => $validated['wifi_ssid'] ?? null,
            'wifi_password' => $validated['wifi_password'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->boolean('remove_logo')) {
            $updateData['logo_url'] = null;
        } elseif ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('menus/logos', 'public');
            $updateData['logo_url'] = Storage::url($path);
        } elseif ($request->filled('logo_url')) {
            $updateData['logo_url'] = $request->input('logo_url');
        }

        if ($request->boolean('remove_cover')) {
            $updateData['cover_image'] = null;
        } elseif ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('menus/covers', 'public');
            $updateData['cover_image'] = Storage::url($path);
        } elseif ($request->filled('cover_image_url')) {
            $updateData['cover_image'] = $request->input('cover_image_url');
        }

        if (isset($validated['categories_json'])) {
            $decoded = json_decode($validated['categories_json'], true);
            if (is_array($decoded)) {
                $updateData['categories'] = $this->processCategoriesImages($decoded);
            }
        }

        $menu->update($updateData);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth('web')->id(),
                'action' => 'restaurant_menu_update',
                'description' => "Updated digital menu '{$menu->title}' (#{$menu->id})",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', "Le menu '{$menu->title}' a été mis à jour.");
    }

    /**
     * Upload an item/dish image asynchronously.
     */
    public function uploadItemImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        $path = $request->file('image')->store('menus/items', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/'.$path),
            'path' => $path,
        ]);
    }

    /**
     * Upload a cover image asynchronously.
     */
    public function uploadCoverImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:6144'],
        ]);

        $path = $request->file('image')->store('menus/covers', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/'.$path),
            'path' => $path,
        ]);
    }

    /**
     * Process categories to convert any embedded base64 images to stored files.
     */
    private function processCategoriesImages(array $categories): array
    {
        foreach ($categories as $cKey => $category) {
            if (! empty($category['items']) && is_array($category['items'])) {
                foreach ($category['items'] as $iKey => $item) {
                    if (! empty($item['image_url']) && str_starts_with($item['image_url'], 'data:image/')) {
                        try {
                            $data = explode(',', $item['image_url']);
                            $extension = 'jpg';
                            if (preg_match('/data:image\/([a-zA-Z0-9]+);base64/', $data[0], $match)) {
                                $ext = strtolower($match[1]);
                                $extension = ($ext === 'jpeg') ? 'jpg' : $ext;
                            }
                            $decoded = base64_decode($data[1]);
                            $filename = 'menus/items/item_'.Str::random(24).'.'.$extension;
                            Storage::disk('public')->put($filename, $decoded);
                            $categories[$cKey]['items'][$iKey]['image_url'] = Storage::url($filename);
                        } catch (\Throwable $e) {
                            // keep fallback
                        }
                    }
                }
            }
        }

        return $categories;
    }

    /**
     * Remove the specified menu.
     */
    public function destroy(RestaurantMenu $menu): RedirectResponse
    {
        $title = $menu->title;
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', "Le menu '{$title}' a été supprimé.");
    }

    /**
     * Toggle menu active / draft status.
     */
    public function toggleStatus(RestaurantMenu $menu): RedirectResponse
    {
        $newStatus = $menu->status === 'active' ? 'draft' : 'active';
        $menu->update(['status' => $newStatus]);

        return back()->with('success', "Le statut du menu est désormais '{$newStatus}'.");
    }

    /**
     * Download table QR code for customer scanning.
     */
    public function downloadQr(RestaurantMenu $menu, string $format): Response
    {
        $publicUrl = route('menu.public.show', $menu->slug);
        $cleanName = Str::slug($menu->brand_name.'-'.$menu->title);

        if ($format === 'svg') {
            $svg = QrCode::format('svg')
                ->size(600)
                ->margin(2)
                ->color(87, 54, 233)
                ->generate($publicUrl);

            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename=\"qr-menu-{$cleanName}.svg\"",
            ]);
        }

        $png = QrCode::format('png')
            ->size(600)
            ->margin(2)
            ->color(87, 54, 233)
            ->generate($publicUrl);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => "attachment; filename=\"qr-menu-{$cleanName}.png\"",
        ]);
    }

    /**
     * Normalize category navigation style between vertical_scroll, horizontal_scroll, and pages.
     */
    protected function normalizeNavStyle(?string $style): string
    {
        return match ($style) {
            'horizontal_scroll', 'grid_drawer' => 'horizontal_scroll',
            'pages', 'tabs' => 'pages',
            default => 'vertical_scroll',
        };
    }
}
