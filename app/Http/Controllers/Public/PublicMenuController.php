<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\RestaurantMenu;
use Illuminate\View\View;

class PublicMenuController extends Controller
{
    /**
     * Display the public interactive restaurant / brand menu.
     */
    public function show(string $slug): View
    {
        $menu = RestaurantMenu::where('slug', $slug)->firstOrFail();

        // Increment scan / view count
        $menu->recordView();

        return view('menus.public', compact('menu'));
    }
}
