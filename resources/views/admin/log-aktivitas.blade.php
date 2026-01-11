@extends('layouts.app')
@section('title', 'Log Aktivitas')

@push('styles')
<style>
    .table-logs { font-size: 0.85rem; }
    .table-logs th { white-space: nowrap; background: #f1f5f9; }
    .badge-action { font-size: 0.7rem; padding: 0.35em 0.65em; }
    .badge-LOGIN { background: #3b82f6; }
    .badge-LOGOUT { background: #64748b; }
    .badge-CREATE, .badge-create { background: #10b981; }
    .badge-UPDATE, .badge-update { background: #f59e0b; }
    .badge-DELETE, .badge-delete { background: #ef4444; }
    .badge-RESTORE, .badge-restore { background: #8b5cf6; }
    .log-detail { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .device-badge { background: #e2e8f0; color: #475569; font-size: 0.7rem; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-history me-2"></i>Log Aktivitas</h4>
    <button type="button" class="btn btn-danger btn-sm" id="btn-clear-all">
        <i class="fas fa-trash me-1"></i>Hapus Semua
    </button>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">ROLE</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ $filterRole === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">AKTIVITAS</label>
                <select name="action_type" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="LOGIN" {{ $filterAction === 'LOGIN' ? 'selected' : '' }}>Login</option>
                    <option value="LOGOUT" {{ $filterAction === 'LOGOUT' ? 'selected' : '' }}>Logout</option>
                    <option value="CREATE" {{ $filterAction === 'CREATE' ? 'selected' : '' }}>Create</option>
                    <option value="UPDATE" {{ $filterAction === 'UPDATE' ? 'selected' : '' }}>Update</option>
                    <option value="DELETE" {{ $filterAction === 'DELETE' ? 'selected' : '' }}>Delete</option>
                    <option value="RESTORE" {{ $filterAction === 'RESTORE' ? 'selected' : '' }}>Restore</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">DARI</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filterDateFrom }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">SAMPAI</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filterDateTo }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-light border btn-sm w-100"><i class="fas fa-times me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div id="bulk-actions" class="bg-danger bg-opacity-10 px-3 py-2 rounded mb-3 d-none">
    <div class="d-flex justify-content-between align-items-center">
        <span class="text-danger fw-bold"><i class="fas fa-check-circle me-1"></i><span id="selected-count">0</span> dipilih</span>
        <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete">
            <i class="fas fa-trash me-1"></i>Hapus Terpilih
        </button>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-logs mb-0">
            <thead>
                <tr>
                    <th width="40"><input type="checkbox" id="select-all" class="form-check-input"></th>
                    <th>No</th>
                    <th>Role</th>
                    <th>Device</th>
                    <th>Aktivitas</th>
                    <th>Detail</th>
                    <th>Waktu</th>
                    <th width="80">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $i => $log)
                    <tr>
                        <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $log->id }}"></td>
                        <td>{{ $offset + $i + 1 }}</td>
                        <td><div class="fw-bold">{{ ucfirst($log->user_role ?? '-') }}</div></td>
                        <td><span class="badge device-badge">{{ $log->device_name ?? '-' }}</span></td>
                        <td><span class="badge badge-action badge-{{ $log->action }}">{{ $log->action }}</span></td>
                        <td class="log-detail">{{ $log->description ?? $log->record_name ?? '-' }}</td>
                        <td><small>{{ date('d/m/Y H:i', strtotime($log->created_at)) }}</small></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-delete-single" data-id="{{ $log->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada log aktivitas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($totalPages > 1)
    <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-center">
            @for($p = 1; $p <= $totalPages; $p++)
                <li class="page-item {{ $p === $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $p }}&role={{ $filterRole }}&action_type={{ $filterAction }}&date_from={{ $filterDateFrom }}&date_to={{ $filterDateTo }}">{{ $p }}</a>
                </li>
            @endfor
        </ul>
    </nav>
@endif

<div class="text-center text-muted mt-2">
    <small>Total: {{ number_format($total) }} log</small>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        toggleBulkActions();
    });
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.addEventListener('change', toggleBulkActions));

    function toggleBulkActions() {
        const count = document.querySelectorAll('.row-checkbox:checked').length;
        document.getElementById('selected-count').textContent = count;
        document.getElementById('bulk-actions').classList.toggle('d-none', count === 0);
    }

    document.getElementById('btn-bulk-delete').addEventListener('click', function() {
        const ids = [...document.querySelectorAll('.row-checkbox:checked')].map(cb => cb.value);
        if (ids.length === 0) return;
        Swal.fire({
            title: 'Hapus ' + ids.length + ' log?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Hapus!'
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.log-aktivitas.bulk-delete") }}';
                form.innerHTML = '@csrf' + ids.map(id => '<input type="hidden" name="ids[]" value="' + id + '">').join('');
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    document.getElementById('btn-clear-all').addEventListener('click', function() {
        Swal.fire({
            title: 'Hapus Semua Log?',
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Hapus Semua!'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("admin.log-aktivitas.clear-all") }}';
            }
        });
    });

    document.querySelectorAll('.btn-delete-single').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Hapus Log Ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url("admin/log-aktivitas") }}/' + id + '/delete';
                }
            });
        });
    });
</script>
@endpush