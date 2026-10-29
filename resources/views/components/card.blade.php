@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'actions' => null,
    'footer' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'card card-custom']) }}>
    @if($title || $actions || isset($header))
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-3">
                @if($icon)
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="{{ $icon }}"></i>
                    </div>
                @endif
                <div>
                    @if($title)
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <span>{{ $title }}</span>
                        </h5>
                    @endif
                    @if($subtitle)
                        <small class="text-muted d-block mt-0 font-sans" style="font-size: 0.8rem;">{{ $subtitle }}</small>
                    @endif
                </div>
            </div>

            @if($actions)
                <div class="card-actions d-flex align-items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? 'p-0' : 'card-body-custom' }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer-custom">
            {{ $footer }}
        </div>
    @endif
</div>
