@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
    </style>
@endpush

@section('content')
    <h4 class="fw-bold mb-4">Dashboard</h4>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-value">{{ $siswaCount ?? 0 }}</div>
                <div class="stat-label">Total Santri</div>
                <div class="stat-icon mx-auto mt-2" style="background: #dbeafe; color: #3b82f6;">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-value">{{ $presentToday ?? 0 }}</div>
                <div class="stat-label">Hadir Hari Ini</div>
                <div class="stat-icon mx-auto mt-2" style="background: #dcfce7; color: #22c55e;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-value">{{ $aktivitasToday ?? 0 }}</div>
                <div class="stat-label">Aktivitas Hari Ini</div>
                <div class="stat-icon mx-auto mt-2" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-value">{{ $userCount ?? 0 }}</div>
                <div class="stat-label">Total User</div>
                <div class="stat-icon mx-auto mt-2" style="background: #dbeafe; color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart -->
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="card-header-custom">
                    <i class="fas fa-chart-bar me-2 text-primary"></i> Grafik Kehadiran 7 Hari Terakhir
                </div>
                <div class="p-3">
                    <div id="attendanceChart"></div>
                </div>
            </div>
        </div>

        <!-- Santri Terlambat -->
        <div class="col-lg-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <i class="fas fa-clock me-2 text-warning"></i> Terlambat Hari Ini
                </div>
                <div class="p-3">
                    @if(isset($lateSiswa) && count($lateSiswa) > 0)
                        @foreach($lateSiswa as $late)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-user text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $late->nama_lengkap }}</div>
                                    <small class="text-muted">
                                        {{ $late->kelas }} - {{ \Carbon\Carbon::parse($late->attendance_time)->format('H:i') }}
                                    </small>
                                </div>
                                <span class="badge bg-warning">{{ $late->minutes_late ?? 0 }} menit</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3" style="color: #22c55e;"></i>
                            <p class="mb-0">Tidak ada siswa terlambat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Aktivitas -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-history me-2 text-primary"></i> Aktivitas Terbaru</span>
                    <a href="{{ route('aktivitas') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Santri</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAktivitas ?? [] as $akt)
                                <tr>
                                    <td>{{ $akt->tanggal ? $akt->tanggal->format('d/m H:i') : '-' }}</td>
                                    <td>
                                        <strong>{{ $akt->santri->nama_lengkap ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $akt->santri->kelas ?? '-' }}</small>
                                    </td>
                                    <td><span
                                            class="badge bg-light text-dark border">{{ str_replace('_', ' ', $akt->kategori) }}</span>
                                    </td>
                                    <td>{{ $akt->judul ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada aktivitas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var chartData = @json($chartData ?? []);
        var options = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [
                { name: 'Hadir', data: chartData.map(d => d.hadir) },
                { name: 'Terlambat', data: chartData.map(d => d.terlambat) }
            ],
            xaxis: { categories: chartData.map(d => d.date) },
            colors: ['#22c55e', '#f59e0b'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: false },
            legend: { position: 'top' }
        };
        new ApexCharts(document.querySelector("#attendanceChart"), options).render();
    </script>
@endpush