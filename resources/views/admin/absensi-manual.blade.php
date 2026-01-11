@extends('layouts.app')
@section('title', 'Absensi Manual')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <h4 class="fw-bold mb-4"><i class="fas fa-edit me-2"></i>Absensi Manual</h4>

    <div class="row g-4">
        <!-- Form -->
        <div class="col-lg-5">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4">Input Absensi</h5>
                <form method="POST" action="{{ route('admin.absensi-manual.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->kelas }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jadwal <span class="text-danger">*</span></label>
                        <select name="jadwal_id" class="form-select" required>
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach($jadwalList as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="attendance_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Waktu <span class="text-danger">*</span></label>
                            <input type="time" name="attendance_time" class="form-control" value="{{ date('H:i') }}"
                                required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="absen">Absen/Alpha</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Simpan Absensi
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent List -->
        <div class="col-lg-7">
            <div class="card-custom">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0">Data Terbaru</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $a)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ date('d/m/Y', strtotime($a->attendance_date)) }}</div>
                                        <small class="text-muted">{{ substr($a->attendance_time, 0, 5) }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $a->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $a->kelas }}</small>
                                    </td>
                                    <td>{{ $a->jadwal_name ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $a->status === 'hadir' ? 'success' : ($a->status === 'terlambat' ? 'warning' : ($a->status === 'absen' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                            data-id="{{ $a->id }}" data-nama="{{ $a->nama_lengkap }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                Swal.fire({
                    title: 'Hapus Data Absensi?',
                    html: 'Data absensi <strong>' + nama + '</strong> akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ url("admin/absensi-manual") }}/' + id;
                        form.innerHTML = '@csrf @method("DELETE")';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush