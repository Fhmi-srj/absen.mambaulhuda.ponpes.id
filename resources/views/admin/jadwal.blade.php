@extends('layouts.app')

@section('title', 'Jadwal Absen')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-clock me-2"></i>Jadwal Absen</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJadwal" onclick="resetForm()">
            <i class="fas fa-plus me-1"></i> Tambah Jadwal
        </button>
    </div>

    <div class="row g-4">
        @forelse($jadwalList as $j)
            <div class="col-md-6 col-lg-4">
                <div class="card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">{{ $j->name }}</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="editJadwal({{ json_encode($j) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                onclick="deleteJadwal({{ $j->id }}, '{{ $j->name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <small class="text-muted d-block">Mulai</small>
                            <strong class="text-success">{{ substr($j->start_time, 0, 5) }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Tepat</small>
                            <strong class="text-primary">{{ substr($j->scheduled_time, 0, 5) }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Tutup</small>
                            <strong class="text-danger">{{ substr($j->end_time, 0, 5) }}</strong>
                        </div>
                    </div>
                    <div class="text-center border-top pt-3">
                        <small class="text-muted">
                            <i class="fas fa-hourglass-half me-1"></i>
                            Toleransi: <strong>{{ $j->late_tolerance_minutes }} menit</strong>
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-custom p-5 text-center">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada jadwal</h5>
                    <p class="text-muted">Tambahkan jadwal absen terlebih dahulu</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalJadwal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formJadwal">
                    @csrf
                    <input type="hidden" name="id" id="form_id">
                    <input type="hidden" name="type" id="f_type" value="absen">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Jadwal <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="f_name" class="form-control" required
                                placeholder="e.g. Absen Masuk">
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label">Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" id="f_start" class="form-control" required step="60">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Tepat Waktu <span class="text-danger">*</span></label>
                                <input type="time" name="scheduled_time" id="f_scheduled" class="form-control" required
                                    step="60">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Tutup <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" id="f_end" class="form-control" required step="60">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">Toleransi Terlambat (menit)</label>
                            <input type="number" name="late_tolerance_minutes" id="f_tolerance" class="form-control"
                                value="15" min="0">
                            <small class="text-muted">Berapa menit setelah waktu tepat masih dianggap tidak
                                terlambat</small>
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
            document.getElementById('f_name').value = '';
            document.getElementById('f_start').value = '';
            document.getElementById('f_scheduled').value = '';
            document.getElementById('f_end').value = '';
            document.getElementById('f_tolerance').value = '15';
            document.getElementById('modalTitle').textContent = 'Tambah Jadwal';
        }

        function editJadwal(j) {
            isEditing = true;
            document.getElementById('form_id').value = j.id;
            document.getElementById('f_name').value = j.name;
            document.getElementById('f_type').value = j.type || 'absen';
            document.getElementById('f_start').value = j.start_time;
            document.getElementById('f_scheduled').value = j.scheduled_time;
            document.getElementById('f_end').value = j.end_time;
            document.getElementById('f_tolerance').value = j.late_tolerance_minutes;
            document.getElementById('modalTitle').textContent = 'Edit Jadwal';
            new bootstrap.Modal(document.getElementById('modalJadwal')).show();
        }

        document.getElementById('formJadwal').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const url = isEditing
                ? '{{ url("api/admin/jadwal") }}/' + id
                : '{{ route("api.admin.jadwal.store") }}';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
        });

        function deleteJadwal(id, name) {
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: 'Jadwal "' + name + '" akan dipindahkan ke trash',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('{{ url("api/admin/jadwal") }}/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
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