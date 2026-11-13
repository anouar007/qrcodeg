<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RestaurantMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClientMenuController extends Controller
{
    /**
     * Display client's digital menus.
     */
    public function index(): View
    {
        $client = auth('client')->user();
        $menus = $client->restaurantMenus()->latest()->paginate(12);

        return view('client.menus.index', compact('menus'));
    }

    /**
     * Show client menu creation form.
     */
    public function create(): View
    {
        $client = auth('client')->user();

        return view('client.menus.create', compact('client'));
    }

    /**
     * Store new client menu.
     */
    public function store(Request $request): RedirectResponse
    {
        $client = auth('client')->user();

        $validated = $request->validate([
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
            'client_id' => $client->id,
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
            'status' => 'active',
        ]);

        return redirect()->route('client.menus.show', $menu)
            ->with('success', "Votre menu '{$menu->title}' a été créé avec succès.");
    }

    /**
     * Show client menu details & QR code.
     */
    public function show(RestaurantMenu $menu): View
    {
        $this->authorizeClientMenu($menu);
        $publicUrl = route('menu.public.show', $menu->slug);

        $qrSvg = (string) QrCode::format('svg')
            ->size(280)
            ->margin(1)
            ->color(87, 54, 233)
            ->generate($publicUrl);

        return view('client.menus.show', compact('menu', 'publicUrl', 'qrSvg'));
    }

    /**
     * Edit client menu.
     */
    public function edit(RestaurantMenu $menu): View
    {
        $this->authorizeClientMenu($menu);

        return view('client.menus.edit', compact('menu'));
    }

    /**
     * Update client menu.
     */
    public function update(Request $request, RestaurantMenu $menu): RedirectResponse
    {
        $this->authorizeClientMenu($menu);

        $validated = $request->validate([
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

        return redirect()->route('client.menus.show', $menu)
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
     * Download client table QR code.
     */
    public function downloadQr(RestaurantMenu $menu, string $format): Response
    {
        $this->authorizeClientMenu($menu);
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

    private function authorizeClientMenu(RestaurantMenu $menu): void
    {
        $client = auth('client')->user();
        if ($menu->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this menu.');
        }
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
