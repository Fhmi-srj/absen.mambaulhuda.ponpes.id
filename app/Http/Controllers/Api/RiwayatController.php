<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $filterDateFrom = $request->get('date_from', '');
        $filterDateTo = $request->get('date_to', '');
        $filterJadwal = $request->get('jadwal', '');
        $filterStatus = $request->get('status', '');
        $filterKelas = $request->get('kelas', '');

        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time')
            ->get();

        $classList = DB::table('data_induk')
            ->whereNull('deleted_at')
            ->whereNotNull('kelas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $query = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name', 'j.type as jadwal_type');

        // Date range filter
        if ($filterDateFrom) {
            $query->where('a.attendance_date', '>=', $filterDateFrom);
        }
        if ($filterDateTo) {
            $query->where('a.attendance_date', '<=', $filterDateTo);
        }

        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }

        if ($filterStatus) {
            $query->where('a.status', $filterStatus);
        }

        if ($filterKelas) {
            $query->where('di.kelas', $filterKelas);
        }

        // Sorting: tanpa filter kelas → urut kelas, dengan filter kelas → urut kedatangan
        if ($filterKelas) {
            $query->orderBy('a.attendance_date', 'desc')->orderBy('a.attendance_time', 'asc');
        } else {
            $query->orderBy('di.kelas', 'asc')->orderBy('di.nama_lengkap', 'asc');
        }

        $attendances = $query->get();

        // Stats
        $statsQuery = DB::table('attendances as a2')
            ->join('data_induk as di2', 'a2.user_id', '=', 'di2.id')
            ->whereNull('a2.deleted_at');

        if ($filterDateFrom) {
            $statsQuery->where('a2.attendance_date', '>=', $filterDateFrom);
        }
        if ($filterDateTo) {
            $statsQuery->where('a2.attendance_date', '<=', $filterDateTo);
        }
        if ($filterJadwal) {
            $statsQuery->where('a2.jadwal_id', $filterJadwal);
        }
        if ($filterKelas) {
            $statsQuery->where('di2.kelas', $filterKelas);
        }
        $allAttendances = $statsQuery->select('a2.status')->get();

        $totalHadir = $allAttendances->where('status', 'hadir')->count();
        $totalTerlambat = $allAttendances->where('status', 'terlambat')->count();
        $totalAlpha = $allAttendances->where('status', 'alpha')->count();

        return response()->json([
            'jadwalList' => $jadwalList,
            'classList' => $classList,
            'attendances' => $attendances,
            'filterDateFrom' => $filterDateFrom,
            'filterDateTo' => $filterDateTo,
            'filterJadwal' => $filterJadwal,
            'filterStatus' => $filterStatus,
            'filterKelas' => $filterKelas,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalAlpha' => $totalAlpha,
        ]);
    }

    public function export(Request $request)
    {
        $filterDateFrom = $request->get('date_from', '');
        $filterDateTo = $request->get('date_to', '');
        $filterJadwal = $request->get('jadwal', '');
        $filterStatus = $request->get('status', '');
        $filterKelas = $request->get('kelas', '');

        $query = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name');

        if ($filterDateFrom) {
            $query->where('a.attendance_date', '>=', $filterDateFrom);
        }
        if ($filterDateTo) {
            $query->where('a.attendance_date', '<=', $filterDateTo);
        }
        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }
        if ($filterStatus) {
            $query->where('a.status', $filterStatus);
        }
        if ($filterKelas) {
            $query->where('di.kelas', $filterKelas);
        }

        $attendances = $query->orderBy('a.attendance_time', 'asc')->get();

        $jadwalName = $filterJadwal
            ? (JadwalAbsen::find($filterJadwal)?->name ?? 'Semua')
            : 'Semua';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Kehadiran');

        $sheet->setCellValue('A1', 'LAPORAN RIWAYAT KEHADIRAN');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $dateLabel = '';
        if ($filterDateFrom && $filterDateTo) {
            $dateLabel = "$filterDateFrom s/d $filterDateTo";
        } elseif ($filterDateFrom) {
            $dateLabel = "Dari $filterDateFrom";
        } elseif ($filterDateTo) {
            $dateLabel = "Sampai $filterDateTo";
        } else {
            $dateLabel = 'Semua';
        }
        $sheet->setCellValue('A2', 'Tanggal: ' . $dateLabel);
        $sheet->setCellValue('A3', 'Jadwal: ' . $jadwalName);
        $sheet->setCellValue('A4', 'Kelas: ' . ($filterKelas ?: 'Semua'));
        $sheet->setCellValue('D4', 'Status: ' . ($filterStatus ?: 'Semua'));

        $headers = ['No', 'Waktu', 'Nama Siswa', 'Kelas', 'Nomor Induk', 'Jadwal', 'Status', 'Terlambat (menit)', 'Catatan'];
        $sheet->fromArray($headers, null, 'A5');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A5:I5')->applyFromArray($headerStyle);
        $sheet->getRowDimension(5)->setRowHeight(20);

        $row = 6;
        foreach ($attendances as $i => $a) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $a->attendance_date . ' ' . ($a->attendance_time ? substr($a->attendance_time, 0, 5) : '-'));
            $sheet->setCellValue('C' . $row, $a->nama_lengkap);
            $sheet->setCellValue('D' . $row, $a->kelas ?? '-');
            $sheet->setCellValue('E' . $row, $a->nomor_induk ?? '-');
            $sheet->setCellValue('F' . $row, $a->jadwal_name ?? '-');
            $sheet->setCellValue('G' . $row, ucfirst($a->status));
            $sheet->setCellValue('H' . $row, $a->minutes_late ?? 0);
            $sheet->setCellValue('I' . $row, $a->notes ?? '-');

            $statusColor = match ($a->status) {
                'hadir' => 'C6EFCE',
                'terlambat' => 'FFEB9C',
                'alpha' => 'FFC7CE',
                'izin', 'sakit' => 'BDE0FE',
                default => 'FFFFFF'
            };
            if ($statusColor !== 'FFFFFF') {
                $sheet->getStyle('G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($statusColor);
            }

            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if ($row > 6) {
            $sheet->getStyle('A5:I' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $filename = "Riwayat_Kehadiran_{$dateLabel}_{$jadwalName}_" . ($filterKelas ?: 'Semua') . ".xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $filterDateFrom = $request->get('date_from', '');
        $filterDateTo = $request->get('date_to', '');
        $filterJadwal = $request->get('jadwal', '');
        $filterStatus = $request->get('status', '');
        $filterKelas = $request->get('kelas', '');

        $query = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name');

        if ($filterDateFrom) {
            $query->where('a.attendance_date', '>=', $filterDateFrom);
        }
        if ($filterDateTo) {
            $query->where('a.attendance_date', '<=', $filterDateTo);
        }
        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }
        if ($filterStatus) {
            $query->where('a.status', $filterStatus);
        }
        if ($filterKelas) {
            $query->where('di.kelas', $filterKelas);
        }

        // Sorting
        if ($filterKelas) {
            $query->orderBy('a.attendance_date', 'asc')->orderBy('a.attendance_time', 'asc');
        } else {
            $query->orderBy('di.kelas', 'asc')->orderBy('di.nama_lengkap', 'asc');
        }

        $attendances = $query->get();

        $jadwalName = $filterJadwal
            ? (JadwalAbsen::find($filterJadwal)?->name ?? 'Semua')
            : 'Semua';

        // Date label
        $dateLabel = '';
        if ($filterDateFrom && $filterDateTo) {
            $dateLabel = "$filterDateFrom s/d $filterDateTo";
        } elseif ($filterDateFrom) {
            $dateLabel = "Dari $filterDateFrom";
        } elseif ($filterDateTo) {
            $dateLabel = "Sampai $filterDateTo";
        } else {
            $dateLabel = 'Semua Tanggal';
        }

        // KOP image as base64
        $kopPath = public_path('KOP PONDOK WARNA.jpg');
        $kopBase64 = '';
        if (file_exists($kopPath)) {
            $kopBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($kopPath));
        }

        return view('pdf.riwayat', [
            'attendances' => $attendances,
            'dateLabel' => $dateLabel,
            'jadwalName' => $jadwalName,
            'filterKelas' => $filterKelas,
            'filterStatus' => $filterStatus,
            'kopBase64' => $kopBase64,
        ]);
    }
}
