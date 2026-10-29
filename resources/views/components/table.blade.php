@props([
    'headers' => [],
    'empty' => 'No records found matching your query.',
    'emptyIcon' => 'bi-inbox',
    'emptyAction' => null,
    'pagination' => null,
    'responsive' => true,
    'striped' => false,
    'hover' => true,
    'isEmpty' => false,
])

<div {{ $attributes->merge(['class' => 'table-custom-wrapper']) }}>
    <div class="{{ $responsive ? 'table-responsive' : '' }}">
        <table class="table table-custom {{ $striped ? 'table-striped' : '' }} {{ $hover ? 'table-hover' : '' }} align-middle">
            @if(!empty($headers))
                <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif

            @if(!$isEmpty)
                <tbody>
                    {{ $slot }}
                </tbody>
            @endif
        </table>
    </div>

    <!-- Empty State Handler -->
    @if($isEmpty)
        <div class="table-empty-state">
            <div class="table-empty-icon shadow-sm">
                <i class="bi {{ $emptyIcon }}"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">No Data Available</h6>
            <p class="text-muted small mb-3 max-w-sm mx-auto">{{ $empty }}</p>
            @if($emptyAction)
                <div>
                    {{ $emptyAction }}
                </div>
            @endif
        </div>
    @endif

    <!-- Pagination Slot -->
    @if($pagination || isset($paginationSlot))
        <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
            {{ $pagination ?? $paginationSlot }}
        </div>
    @endif
</div>
