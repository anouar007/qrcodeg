@extends('layouts.admin')

@section('title', 'QR Design Templates')
@section('page_title', 'Design & Landing Templates')
@section('page_subtitle', 'Reusable layout presets, color tokens, and pre-packaged industry configurations')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Template Library</h5>
            <small class="text-muted">Accelerate creation for hotels, restaurants, products, and networking</small>
        </div>
        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary-custom rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> New Template
        </a>
    </div>

    <div class="row g-4">
        @forelse($templates as $tmpl)
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white border">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $tmpl->type }}</span>
                        @if($tmpl->is_active)
                            <span class="badge bg-success-subtle text-success">Active</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                        @endif
                    </div>

                    <h5 class="fw-bold text-dark mb-2">{{ $tmpl->name }}</h5>
                    <p class="text-muted small mb-3">{{ $tmpl->description }}</p>

                    <!-- Palette swatch preview -->
                    @if(!empty($tmpl->default_colors))
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <small class="text-muted" style="font-size: 0.72rem;">PALETTE:</small>
                            @foreach($tmpl->default_colors as $color)
                                <div class="rounded-circle border" style="width: 20px; height: 20px; background-color: {{ $color }};" title="{{ $color }}"></div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2 mt-auto pt-3 border-top">
                        <a href="{{ route('admin.templates.edit', $tmpl) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.templates.destroy', $tmpl) }}" method="POST" onsubmit="return confirm('Delete this template?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-palette fs-2 d-block mb-2"></i>
                No templates configured yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
