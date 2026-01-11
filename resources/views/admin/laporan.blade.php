@extends('layouts.app')
@section('title', 'Laporan Absensi')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0"><i class="fas fa-chart-bar me-2"></i>Laporan Absensi</h4>
    <a href="?{{ http_build_query(array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">
        <i class="fas fa-file-excel me-1"></i> Export Excel
    </a>
</div>

<!-- Filters -->
<div class="card-custom p-3 mb-4">
    <form class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label small text-muted">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Siswa</label>
            <select name="siswa_id" class="form-select">
                <option value="">Semua Siswa</option>
                @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" {{ $filterSiswa == $s->id ? 'selected' : '' }}>{{ $s->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Jadwal</label>
            <select name="jadwal_id" class="form-select">
                <option value="">Semua</option>
                @foreach($jadwalList as $j)
                    <option value="{{ $j->id }}" {{ $filterJadwal == $j->id ? 'selected' : '' }}>{{ $j->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua</option>
                <option value="hadir" {{ $filterStatus === 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="terlambat" {{ $filterStatus === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="izin" {{ $filterStatus === 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ $filterStatus === 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="absen" {{ $filterStatus === 'absen' ? 'selected' : '' }}>Absen</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center bg-primary bg-opacity-10">
            <div class="fs-4 fw-bold text-primary">{{ $stats->total ?? 0 }}</div>
            <div class="small text-muted">Total Record</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center bg-success bg-opacity-10">
            <div class="fs-4 fw-bold text-success">{{ $stats->hadir ?? 0 }}</div>
            <div class="small text-muted">Hadir</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center bg-warning bg-opacity-10">
            <div class="fs-4 fw-bold text-warning">{{ $stats->terlambat ?? 0 }}</div>
            <div class="small text-muted">Terlambat</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center bg-danger bg-opacity-10">
            <div class="fs-4 fw-bold text-danger">{{ $stats->tidak_hadir ?? 0 }}</div>
            <div class="small text-muted">Tidak Hadir</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Terlambat</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $a)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ date('d/m/Y', strtotime($a->attendance_date)) }}</div>
                            <small class="text-muted">{{ substr($a->attendance_time, 0, 5) }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $a->nama_lengkap }}</div>
                            <small class="text-muted">{{ $a->kelas }} | {{ $a->nomor_induk }}</small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $a->jadwal_name ?? '-' }}</span></td>
                        <td>
                            <span class="badge bg-{{ $a->status === 'hadir' ? 'success' : ($a->status === 'terlambat' ? 'warning' : 'danger') }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                        <td>{{ $a->minutes_late ? $a->minutes_late . ' menit' : '-' }}</td>
                        <td>{{ $a->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection