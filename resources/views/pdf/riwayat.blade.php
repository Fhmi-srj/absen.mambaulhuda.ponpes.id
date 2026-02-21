<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Riwayat Kehadiran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; color: #333; padding: 10px; }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            @page { margin: 10mm 10mm 10mm 10mm; }
        }

        /* Print button */
        .no-print {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 999;
            display: flex;
            gap: 8px;
        }
        .no-print button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-print { background: #2563eb; color: #fff; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-close { background: #ef4444; color: #fff; }
        .btn-close:hover { background: #dc2626; }

        /* KOP */
        .kop-container { text-align: center; padding-bottom: 0; }
        .kop-container img { width: 100%; height: auto; }
        .report-title { text-align: center; font-size: 13px; font-weight: bold; margin: 8px 0 6px 0; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Info section */
        .info-section { margin-bottom: 8px; font-size: 10px; }
        .info-section table { width: 100%; }
        .info-section td { padding: 1px 0; }
        .info-section .label { font-weight: bold; width: 80px; }

        /* Main table */
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead { display: table-header-group; } /* Repeat on every page */
        table.data th {
            background: #2563eb;
            color: #fff;
            font-weight: bold;
            padding: 5px 6px;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #999;
        }
        table.data td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            font-size: 10px;
        }
        table.data tr:nth-child(even) { background: #f5f5f5; }
        .text-center { text-align: center; }

        .badge { padding: 1px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-hadir { background: #d1fae5; color: #065f46; }
        .badge-terlambat { background: #fef3c7; color: #92400e; }
        .badge-alpha { background: #fee2e2; color: #991b1b; }

        .no-data { text-align: center; padding: 30px; color: #999; font-style: italic; }
        .footer { margin-top: 10px; text-align: right; font-size: 9px; color: #888; }

        @media print {
            table.data th { background: #2563eb !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table.data tr:nth-child(even) { background: #f5f5f5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @if(!request()->get('embed'))
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak</button>
        <button class="btn-close" onclick="window.close()">✕ Tutup</button>
    </div>
    @endif

    @if($attendances->count() > 0)
    <table class="data">
        <thead>
            <tr>
                <td colspan="5" style="border: none; padding: 0;">
                    <div class="kop-container">
                        @if($kopBase64)
                        <img src="{{ $kopBase64 }}" alt="Kop Pondok">
                        @endif
                    </div>
                    <div class="report-title">Rekap Absensi {{ $jadwalName !== 'Semua' ? $jadwalName : '' }} {{ $filterKelas ? 'Kelas ' . $filterKelas : '' }}</div>
                    <div class="info-section">
                        <table>
                            <tr>
                                <td class="label">Tanggal</td>
                                <td>: {{ $dateLabel }}</td>
                                <td class="label" style="width:60px">Jadwal</td>
                                <td>: {{ $jadwalName }}</td>
                            </tr>
                            <tr>
                                <td class="label">Kelas</td>
                                <td>: {{ $filterKelas ?: 'Semua' }}</td>
                                <td class="label">Status</td>
                                <td>: {{ $filterStatus ? ucfirst($filterStatus) : 'Semua' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama</th>
                <th style="width:60px">Kelas</th>
                <th style="width:120px">Kedatangan</th>
                <th style="width:80px">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $i => $a)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $a->nama_lengkap }}</td>
                <td class="text-center">{{ $a->kelas ?? '-' }}</td>
                <td class="text-center">
                    @if($a->attendance_time && $a->attendance_time !== '-')
                        {{ \Carbon\Carbon::parse($a->attendance_date)->translatedFormat('d M Y') }}<br>
                        {{ substr($a->attendance_time, 0, 5) }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @php
                        $badgeClass = match($a->status) {
                            'hadir' => 'badge-hadir',
                            'terlambat' => 'badge-terlambat',
                            'alpha' => 'badge-alpha',
                            default => '',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($a->status) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="kop-container">
        @if($kopBase64)
        <img src="{{ $kopBase64 }}" alt="Kop Pondok">
        @endif
    </div>
    <div class="report-title">Rekap Absensi</div>
    <div class="no-data">Tidak ada data absensi</div>
    @endif

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>
