@props([
    'status' => null,
    'color' => null,
    'dot' => true,
    'label' => null,
])

@php
    $normalizedStatus = strtolower(trim((string) $status));

    // Intelligent auto-mapping of status to aesthetic color scheme
    if (!$color) {
        $color = match ($normalizedStatus) {
            'active', 'published', 'completed', 'paid', 'verified', 'operational' => 'success',
            'inactive', 'draft', 'paused', 'unverified' => 'secondary',
            'pending', 'processing', 'in_progress' => 'warning',
            'failed', 'deleted', 'banned', 'expired', 'error', 'rejected' => 'danger',
            'locked', 'archived', 'closed' => 'secondary',
            default => 'primary',
        };
    }

    $displayLabel = $label ?: ($slot->isNotEmpty() ? $slot : ucfirst(str_replace('_', ' ', $status ?: 'Active')));
@endphp

<span {{ $attributes->merge(['class' => 'badge-status badge-status-' . $color]) }}>
    @if($dot)
        <span class="status-dot"></span>
    @endif
    <span>{{ $displayLabel }}</span>
</span>
