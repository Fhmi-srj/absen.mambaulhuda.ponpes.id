@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Manajemen User</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Tambah User
    </button>
</div>

<div class="card-custom">
    <div class="p-3 border-bottom">
        <select class="form-select w-auto" onchange="location.href='{{ route('admin.pengguna') }}?role='+this.value">
            <option value="">Semua Role</option>
            @foreach($roles as $r)
                <option value="{{ $r }}" {{ $filterRole === $r ? 'selected' : '' }}>
                    {{ $roleLabels[$r] }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Device</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td><strong>{{ $u->name }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge bg-{{ $u->role === 'admin' ? 'danger' : 'secondary' }}">
                                {{ $roleLabels[$u->role] ?? ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>{{ $u->phone ?? '-' }}</td>
                        <td>
                            @if($u->device_count > 0)
                                <i class="fas fa-mobile-alt text-success"></i>
                            @else
                                <i class="fas fa-mobile-alt text-muted"></i>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" 
                                onclick="editUser({{ json_encode($u) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($u->device_count > 0)
                                <button class="btn btn-sm btn-outline-info" onclick="resetDevice({{ $u->id }})">
                                    <i class="fas fa-sync"></i>
                                </button>
                            @endif
                            @if($u->id != auth()->id())
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $u->id }}, '{{ $u->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formUser">
                @csrf
                <input type="hidden" name="id" id="form_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="form_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="form_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak ubah)</small></label>
                        <input type="password" name="password" id="form_password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" id="form_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" id="form_address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="form_role" class="form-select" required>
                            @foreach($roles as $r)
                                <option value="{{ $r }}">{{ $roleLabels[$r] }}</option>
                            @endforeach
                        </select>
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
    let isEditing = false;

    function resetForm() {
        isEditing = false;
        document.getElementById('form_id').value = '';
        document.getElementById('form_name').value = '';
        document.getElementById('form_email').value = '';
        document.getElementById('form_password').value = '';
        document.getElementById('form_phone').value = '';
        document.getElementById('form_address').value = '';
        document.getElementById('form_role').value = 'karyawan';
        document.getElementById('modalTitle').textContent = 'Tambah User';
        document.getElementById('form_password').required = true;
    }

    function editUser(u) {
        isEditing = true;
        document.getElementById('form_id').value = u.id;
        document.getElementById('form_name').value = u.name;
        document.getElementById('form_email').value = u.email;
        document.getElementById('form_password').value = '';
        document.getElementById('form_phone').value = u.phone || '';
        document.getElementById('form_address').value = u.address || '';
        document.getElementById('form_role').value = u.role;
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('form_password').required = false;
        new bootstrap.Modal(document.getElementById('modalUser')).show();
    }

    document.getElementById('formUser').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = formData.get('id');
        const url = isEditing 
            ? '{{ url("api/admin/pengguna") }}/' + id
            : '{{ route("api.admin.pengguna.store") }}';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', 'Terjadi kesalahan', 'error');
        });
    });

    function resetDevice(id) {
        Swal.fire({
            title: 'Reset Device?',
            text: 'User harus login ulang di device baru',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('{{ url("api/admin/pengguna") }}/' + id + '/reset-device', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }

    function deleteUser(id, name) {
        Swal.fire({
            title: 'Hapus User?',
            text: 'User "' + name + '" akan dipindahkan ke trash',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('{{ url("api/admin/pengguna") }}/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endpush