<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\QrTemplate;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $templates = QrTemplate::where('is_active', true)->take(6)->get();

        return view('public.home', compact('templates'));
    }

    public function features()
    {
        return view('public.features');
    }

    public function types()
    {
        return view('public.types');
    }

    public function pricing()
    {
        return view('public.pricing');
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! Your message has been received. Our team will contact you shortly.');
    }
}
