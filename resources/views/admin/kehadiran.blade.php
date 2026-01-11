@extends('layouts.app')
@section('title', 'Data Absensi')

@push('styles')
<style>
    .stat-mini { padding: 1rem; border-radius: 12px; text-align: center; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<h4 class="fw-bold mb-4"><i class="fas fa-calendar-check me-2"></i>Data Absensi</h4>

<!-- Filters -->
<div class="card-custom p-3 mb-4">
    <form class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small text-muted">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ $filterDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Jadwal</label>
            <select name="jadwal" class="form-select">
                <option value="">Semua Jadwal</option>
                @foreach($jadwalList as $j)
                    <option value="{{ $j->id }}" {{ $filterJadwal == $j->id ? 'selected' : '' }}>{{ $j->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini bg-primary bg-opacity-10">
            <div class="fs-3 fw-bold text-primary">{{ count($attendances) }}</div>
            <div class="small text-muted">Total Absensi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini bg-success bg-opacity-10">
            <div class="fs-3 fw-bold text-success">{{ $totalHadir }}</div>
            <div class="small text-muted">Hadir</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini bg-warning bg-opacity-10">
            <div class="fs-3 fw-bold text-warning">{{ $totalTerlambat }}</div>
            <div class="small text-muted">Terlambat</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini bg-danger bg-opacity-10">
            <div class="fs-3 fw-bold text-danger">{{ $totalAbsen }}</div>
            <div class="small text-muted">Absen</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Waktu</th>
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
                        <td><strong>{{ date('H:i', strtotime($a->attendance_time)) }}</strong></td>
                        <td>
                            <div class="fw-semibold">{{ $a->nama_lengkap }}</div>
                            <small class="text-muted">{{ $a->kelas }} - {{ $a->nomor_induk }}</small>
                        </td>
                        <td><span class="badge bg-primary">{{ $a->jadwal_name ?? '-' }}</span></td>
                        <td>
                            @if($a->status === 'terlambat')<span class="badge bg-warning">Terlambat</span>
                            @elseif($a->status === 'hadir')<span class="badge bg-success">Hadir</span>
                            @elseif($a->status === 'absen')<span class="badge bg-danger">Absen</span>
                            @elseif($a->status === 'izin')<span class="badge bg-info">Izin</span>
                            @else<span class="badge bg-secondary">{{ $a->status }}</span>@endif
                        </td>
                        <td>{{ $a->minutes_late ? $a->minutes_late . ' menit' : '-' }}</td>
                        <td>{{ $a->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data absensi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection