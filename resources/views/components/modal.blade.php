@props([
    'id',
    'title' => 'Modal Title',
    'icon' => null,
    'size' => 'md',
    'centered' => true,
    'backdrop' => 'static',
    'footer' => null,
])

@php
    $sizeClass = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        'fullscreen' => 'modal-fullscreen',
        default => '',
    };
@endphp

<div class="modal fade modal-custom" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true" data-bs-backdrop="{{ $backdrop }}" {{ $attributes }}>
    <div class="modal-dialog {{ $sizeClass }} {{ $centered ? 'modal-dialog-centered' : '' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="{{ $id }}Label">
                    @if($icon)
                        <i class="bi {{ $icon }} text-primary"></i>
                    @endif
                    <span>{{ $title }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            @if($footer || isset($footerSlot))
                <div class="modal-footer">
                    {{ $footer ?? $footerSlot }}
                </div>
            @endif
        </div>
    </div>
</div>
