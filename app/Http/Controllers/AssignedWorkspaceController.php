<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AssignedWorkspaceController extends Controller
{
    /**
     * Show the user's assigned menus and digital cards workspace.
     */
    public function index(): View
    {
        $user = auth('web')->user();

        $menus = $user->assignedMenus()->latest()->get();
        $digitalCards = $user->assignedDigitalCards()->latest()->get();

        return view('assigned_workspace.index', compact('user', 'menus', 'digitalCards'));
    }
}
