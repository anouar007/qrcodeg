<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\QrTemplate;
use Illuminate\Http\Request;

class QrTemplateController extends Controller
{
    public function index()
    {
        $templates = QrTemplate::latest()->paginate(15);

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:custom_landing,contact,wifi,business,product,website,social',
            'description' => 'nullable|string|max:500',
            'primary_color' => 'required|string|max:16',
            'background_color' => 'required|string|max:16',
            'text_color' => 'required|string|max:16',
            'preset_heading' => 'nullable|string|max:150',
            'preset_paragraph' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $template = QrTemplate::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'default_colors' => [
                'primary' => $validated['primary_color'],
                'background' => $validated['background_color'],
                'text' => $validated['text_color'],
            ],
            'content_preset' => [
                'heading' => $validated['preset_heading'] ?? $validated['name'],
                'paragraph' => $validated['preset_paragraph'] ?? '',
            ],
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log(
            action: 'template_created',
            resourceType: 'QrTemplate',
            resourceId: $template->id,
            details: ['name' => $template->name, 'type' => $template->type]
        );

        return redirect()->route('admin.templates.index')->with('success', "Template {$template->name} created successfully.");
    }

    public function edit(QrTemplate $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, QrTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:custom_landing,contact,wifi,business,product,website,social',
            'description' => 'nullable|string|max:500',
            'primary_color' => 'required|string|max:16',
            'background_color' => 'required|string|max:16',
            'text_color' => 'required|string|max:16',
            'preset_heading' => 'nullable|string|max:150',
            'preset_paragraph' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'default_colors' => [
                'primary' => $validated['primary_color'],
                'background' => $validated['background_color'],
                'text' => $validated['text_color'],
            ],
            'content_preset' => [
                'heading' => $validated['preset_heading'] ?? $template->name,
                'paragraph' => $validated['preset_paragraph'] ?? '',
            ],
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log(
            action: 'template_updated',
            resourceType: 'QrTemplate',
            resourceId: $template->id,
            details: ['name' => $template->name]
        );

        return redirect()->route('admin.templates.index')->with('success', "Template {$template->name} updated.");
    }

    public function destroy(QrTemplate $template)
    {
        $name = $template->name;
        $template->delete();

        ActivityLog::log(
            action: 'template_deleted',
            resourceType: 'QrTemplate',
            resourceId: $template->id,
            details: ['name' => $name]
        );

        return redirect()->route('admin.templates.index')->with('success', "Template {$name} deleted.");
    }
}
