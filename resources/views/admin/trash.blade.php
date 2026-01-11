@extends('layouts.app')
@section('title', 'Trash')

@push('styles')
    <style>
        .nav-tabs-trash .nav-link {
            color: #64748b;
            border: none;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs-trash .nav-link.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            background: none;
        }

        .nav-tabs-trash .nav-link .badge {
            font-size: 0.65rem;
        }

        .trash-table {
            font-size: 0.85rem;
        }

        .trash-table th {
            background: #f8fafc;
            white-space: nowrap;
        }

        .deleted-info {
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-trash-restore me-2"></i>Trash</h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalSettings">
                <i class="fas fa-cog me-1"></i>Pengaturan
            </button>
            <button type="button" class="btn btn-danger btn-sm" id="btn-empty-trash">
                <i class="fas fa-trash me-1"></i>Kosongkan Trash
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-trash mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'santri' ? 'active' : '' }}" href="?tab=santri">
                <i class="fas fa-users me-1"></i>Santri <span
                    class="badge bg-secondary">{{ count($trashData['santri']) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'aktivitas' ? 'active' : '' }}" href="?tab=aktivitas">
                <i class="fas fa-clipboard-list me-1"></i>Aktivitas <span
                    class="badge bg-secondary">{{ count($trashData['aktivitas']) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'absensi' ? 'active' : '' }}" href="?tab=absensi">
                <i class="fas fa-calendar-check me-1"></i>Absensi <span
                    class="badge bg-secondary">{{ count($trashData['absensi']) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}" href="?tab=users">
                <i class="fas fa-user-cog me-1"></i>Users <span
                    class="badge bg-secondary">{{ count($trashData['users']) }}</span>
            </a>
        </li>
    </ul>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="bg-primary bg-opacity-10 px-3 py-2 rounded mb-3 d-none">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-primary fw-bold"><i class="fas fa-check-circle me-1"></i><span id="selected-count">0</span>
                dipilih</span>
            <div>
                <button type="button" class="btn btn-success btn-sm me-2" id="btn-bulk-restore"><i
                        class="fas fa-undo me-1"></i>Restore</button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete"><i
                        class="fas fa-times me-1"></i>Hapus Permanen</button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            @php
                $tableMap = ['santri' => 'data_induk', 'aktivitas' => 'catatan_aktivitas', 'absensi' => 'attendances', 'users' => 'users'];
                $currentTable = $tableMap[$activeTab] ?? 'data_induk';
            @endphp
            <input type="hidden" id="current-table" value="{{ $currentTable }}">

            @if($activeTab === 'santri')
                @include('admin.trash._santri', ['data' => $trashData['santri'], 'deleters' => $deleters])
            @elseif($activeTab === 'aktivitas')
                @include('admin.trash._aktivitas', ['data' => $trashData['aktivitas']])
            @elseif($activeTab === 'absensi')
                @include('admin.trash._absensi', ['data' => $trashData['absensi']])
            @elseif($activeTab === 'users')
                @include('admin.trash._users', ['data' => $trashData['users']])
            @endif
        </div>
    </div>

    <!-- Modal Settings -->
    <div class="modal fade" id="modalSettings" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.trash.settings') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h6 class="modal-title"><i class="fas fa-cog me-2"></i>Pengaturan Trash</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="auto_purge_enabled"
                                id="auto_purge_enabled" {{ $autoPurgeEnabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_purge_enabled">Aktifkan Auto-Hapus Permanen</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hapus otomatis setelah (hari)</label>
                            <input type="number" name="auto_purge_days" class="form-control" value="{{ $autoPurgeDays }}"
                                min="1" max="365">
                            <small class="text-muted">Data di trash akan dihapus permanen setelah X hari</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const currentTable = document.getElementById('current-table').value;

        document.getElementById('select-all')?.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
            toggleBulkActions();
        });
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.addEventListener('change', toggleBulkActions));

        function toggleBulkActions() {
            const count = document.querySelectorAll('.row-checkbox:checked').length;
            document.getElementById('selected-count').textContent = count;
            document.getElementById('bulk-actions').classList.toggle('d-none', count === 0);
        }

        function getSelectedIds() {
            return [...document.querySelectorAll('.row-checkbox:checked')].map(cb => cb.value);
        }

        document.getElementById('btn-bulk-restore')?.addEventListener('click', function () {
            const ids = getSelectedIds();
            if (ids.length === 0) return;
            Swal.fire({
                title: 'Restore ' + ids.length + ' data?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Restore!'
            }).then(result => {
                if (result.isConfirmed) submitBulkAction('bulk_restore', ids);
            });
        });

        document.getElementById('btn-bulk-delete')?.addEventListener('click', function () {
            const ids = getSelectedIds();
            if (ids.length === 0) return;
            Swal.fire({
                title: 'Hapus Permanen ' + ids.length + ' data?',
                text: 'Tindakan ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus Permanen!'
            }).then(result => {
                if (result.isConfirmed) submitBulkAction('bulk_delete', ids);
            });
        });

        document.getElementById('btn-empty-trash')?.addEventListener('click', function () {
            Swal.fire({
                title: 'Kosongkan Semua Trash?',
                text: 'Semua data di trash akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Kosongkan!'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.trash.empty") }}';
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        function submitBulkAction(action, ids) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action === 'bulk_restore' ? '{{ route("admin.trash.bulk-restore") }}' : '{{ route("admin.trash.bulk-delete") }}';
            form.innerHTML = '@csrf<input type="hidden" name="table" value="' + currentTable + '">' + ids.map(id => '<input type="hidden" name="ids[]" value="' + id + '">').join('');
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush