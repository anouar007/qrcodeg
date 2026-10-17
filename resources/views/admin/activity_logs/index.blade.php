@extends('layouts.admin')

@section('title', 'System Activity Logs')
@section('page_title', 'Audit & Activity Trail')
@section('page_subtitle', 'Comprehensive security log of administrative actions, scans, and credential changes')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">System Activity Logs</h5>
            <small class="text-muted">Total: {{ $logs->total() }} recorded audit actions</small>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search IP, action, resource..." value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="action" class="form-select">
                <option value="">All Action Types</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="user_id" class="form-select">
                <option value="">All Users</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light text-uppercase tracking-wider" style="font-size: 0.72rem;">
                <tr>
                    <th>Timestamp</th>
                    <th>User / Client</th>
                    <th>Action</th>
                    <th>Target Resource</th>
                    <th>IP Address</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-nowrap text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($log->user)
                                <div class="fw-semibold text-dark">{{ $log->user->name }}</div>
                                <span class="badge bg-primary-subtle text-primary" style="font-size: 0.65rem;">STAFF</span>
                            @elseif($log->client)
                                <div class="fw-semibold text-dark">{{ $log->client->company_name }}</div>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">CLIENT</span>
                            @else
                                <span class="text-muted">System / Public</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border font-mono">{{ $log->action }}</span>
                        </td>
                        <td>
                            @if($log->resource_type)
                                <span class="text-dark fw-semibold">{{ $log->resource_type }}</span>
                                <code class="text-muted" style="font-size: 0.72rem;">#{{ $log->resource_id }}</code>
                            @else
                                <span class="text-muted">&mdash;</span>
                            @endif
                        </td>
                        <td>
                            <code class="text-muted">{{ $log->ip_address ?: '127.0.0.1' }}</code>
                        </td>
                        <td>
                            @if(!empty($log->details))
                                <span class="text-muted text-truncate d-inline-block font-mono" style="max-width: 250px; font-size: 0.72rem;" title="{{ json_encode($log->details) }}">
                                    {{ json_encode($log->details) }}
                                </span>
                            @else
                                <span class="text-muted">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                            No activity logs recorded matching criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
