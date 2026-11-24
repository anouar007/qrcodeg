<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Workspace — {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .workspace-shell {
            max-width: 960px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
        }
        .workspace-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }
    </style>
</head>
<body>
    <div class="workspace-shell">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-grid-1x2-fill fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">My Assigned Workspace</h4>
                    <div class="small text-muted">{{ $user->name }} &bull; {{ $user->email }}</div>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 small mb-4">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <!-- Assigned Menus -->
            <div class="col-12 col-lg-6">
                <div class="workspace-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-journal-richtext text-primary me-2"></i>Assigned Menus</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $menus->count() }}</span>
                    </div>

                    @forelse($menus as $menu)
                        <div class="d-flex align-items-center justify-content-between gap-2 p-3 border rounded-3 mb-2 bg-light-subtle">
                            <div class="min-w-0">
                                <div class="fw-bold text-dark text-truncate">{{ $menu->title }}</div>
                                <div class="small text-muted text-truncate">{{ $menu->brand_name }}</div>
                            </div>
                            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-primary rounded-pill px-3 flex-shrink-0">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            No menus assigned to your account.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Assigned Digital Cards -->
            <div class="col-12 col-lg-6">
                <div class="workspace-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-person-vcard text-info me-2"></i>Assigned Digital Cards</h5>
                        <span class="badge bg-info-subtle text-info rounded-pill">{{ $digitalCards->count() }}</span>
                    </div>

                    @forelse($digitalCards as $card)
                        <div class="d-flex align-items-center justify-content-between gap-2 p-3 border rounded-3 mb-2 bg-light-subtle">
                            <div class="min-w-0">
                                <div class="fw-bold text-dark text-truncate">{{ $card->full_name ?: $card->name }}</div>
                                <div class="small text-muted text-truncate font-mono">{{ $card->slug }}</div>
                            </div>
                            <a href="{{ route('admin.digital-cards.edit', $card) }}" class="btn btn-sm btn-info text-white rounded-pill px-3 flex-shrink-0">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            No digital cards assigned to your account.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
