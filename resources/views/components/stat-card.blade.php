@props([
    'title',
    'value',
    'icon' => 'bi-graph-up',
    'trend' => null,
    'trendDirection' => null,
    'trendLabel' => 'vs last month',
    'color' => 'primary',
    'progress' => null,
])

@php
    // Auto-detect trend direction if not explicitly supplied
    if (!$trendDirection && $trend) {
        if (str_starts_with(trim($trend), '+')) {
            $trendDirection = 'up';
        } elseif (str_starts_with(trim($trend), '-')) {
            $trendDirection = 'down';
        } else {
            $trendDirection = 'neutral';
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'stat-card stat-' . $color . ' position-relative overflow-hidden']) }}>
    <div class="d-flex align-items-start justify-content-between mb-3">
        <div>
            <div class="d-flex align-items-center gap-1 mb-1">
                <span class="status-dot-radar" style="width: 6px; height: 6px;"></span>
                <span class="text-muted small fw-semibold text-uppercase tracking-wider font-mono" style="font-size: 0.72rem; letter-spacing: 0.08em;">{{ $title }}</span>
            </div>
            <h3 class="fw-extrabold text-dark mb-0 fs-2 font-mono tracking-tight">{{ $value }}</h3>
        </div>

        <div class="stat-icon-wrapper stat-icon-{{ $color }} shadow-xs">
            <i class="bi {{ $icon }}"></i>
        </div>
    </div>

    <!-- Trend Indicator or Micro Progress -->
    @if($trend || $progress !== null || $slot->isNotEmpty())
        <div class="pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between">
            @if($trend)
                <div class="d-flex align-items-center gap-2">
                    <span class="stat-trend-badge stat-trend-{{ $trendDirection }}">
                        @if($trendDirection === 'up')
                            <i class="bi bi-arrow-up-right"></i>
                        @elseif($trendDirection === 'down')
                            <i class="bi bi-arrow-down-right"></i>
                        @else
                            <i class="bi bi-dash"></i>
                        @endif
                        {{ $trend }}
                    </span>
                    @if($trendLabel)
                        <span class="text-muted" style="font-size: 0.75rem;">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif

            @if($progress !== null)
                <div class="w-100 {{ $trend ? 'ms-3' : '' }}">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </div>
    @endif
</div>
