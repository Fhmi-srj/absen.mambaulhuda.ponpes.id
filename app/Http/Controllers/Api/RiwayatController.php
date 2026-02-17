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
        $filterDate = $request->get('date', date('Y-m-d'));
        $filterJadwal = $request->get('jadwal', '');
        $filterStatus = $request->get('status', '');

        $jadwalList = \App\Models\JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time')
            ->get();

        $query = \Illuminate\Support\Facades\DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->where('a.attendance_date', $filterDate)
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name', 'j.type as jadwal_type');

        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }

        if ($filterStatus) {
            $query->where('a.status', $filterStatus);
        }

        $attendances = $query->orderBy('a.attendance_time', 'desc')->get();

        // Calculate stats for the whole day (ignores status filter for stats reliability)
        $statsQuery = \Illuminate\Support\Facades\DB::table('attendances')
            ->whereNull('deleted_at')
            ->where('attendance_date', $filterDate);
        if ($filterJadwal) {
            $statsQuery->where('jadwal_id', $filterJadwal);
        }
        $allAttendances = $statsQuery->get();

        $totalHadir = $allAttendances->where('status', 'hadir')->count();
        $totalTerlambat = $allAttendances->where('status', 'terlambat')->count();
        $totalAbsen = $allAttendances->where('status', 'absen')->count();

        return response()->json([
            'jadwalList' => $jadwalList,
            'attendances' => $attendances,
            'filterDate' => $filterDate,
            'filterJadwal' => $filterJadwal,
            'filterStatus' => $filterStatus,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalAbsen' => $totalAbsen,
        ]);
    }

    public function export(Request $request)
    {
        $filterDate = $request->get('date', date('Y-m-d'));
        $filterJadwal = $request->get('jadwal', '');
        $filterStatus = $request->get('status', '');

        $query = \Illuminate\Support\Facades\DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->where('a.attendance_date', $filterDate)
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name');

        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }

        if ($filterStatus) {
            $query->where('a.status', $filterStatus);
        }

        $attendances = $query->orderBy('a.attendance_time', 'asc')->get();

        $jadwalName = $filterJadwal
            ? (\App\Models\JadwalAbsen::find($filterJadwal)?->name ?? 'Semua')
            : 'Semua';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Kehadiran');

        // Title and Info Rows
        $sheet->setCellValue('A1', 'LAPORAN RIWAYAT KEHADIRAN');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal: ' . $filterDate);
        $sheet->setCellValue('A3', 'Jadwal: ' . $jadwalName);
        $sheet->setCellValue('A4', 'Status: ' . ($filterStatus ?: 'Semua'));

        // Header Row
        $headers = ['No', 'Waktu', 'Nama Siswa', 'Kelas', 'Nomor Induk', 'Jadwal', 'Status', 'Terlambat (menit)', 'Catatan'];
        $sheet->fromArray($headers, null, 'A5');

        // Header Styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A5:I5')->applyFromArray($headerStyle);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Data Rows
        $row = 6;
        foreach ($attendances as $i => $a) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $a->attendance_time ? substr($a->attendance_time, 0, 5) : '-');
            $sheet->setCellValue('C' . $row, $a->nama_lengkap);
            $sheet->setCellValue('D' . $row, $a->kelas ?? '-');
            $sheet->setCellValue('E' . $row, $a->nomor_induk ?? '-');
            $sheet->setCellValue('F' . $row, $a->jadwal_name ?? '-');
            $sheet->setCellValue('G' . $row, ucfirst($a->status));
            $sheet->setCellValue('H' . $row, $a->minutes_late ?? 0);
            $sheet->setCellValue('I' . $row, $a->notes ?? '-');

            // Optional: Status Styling
            $statusColor = match ($a->status) {
                'hadir' => 'C6EFCE',
                'terlambat' => 'FFEB9C',
                'absen', 'izin', 'sakit' => 'FFC7CE',
                default => 'FFFFFF'
            };
            if ($statusColor !== 'FFFFFF') {
                $sheet->getStyle('G' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($statusColor);
            }

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Borders for all data
        if ($row > 6) {
            $sheet->getStyle('A5:I' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        $filename = "Riwayat_Kehadiran_{$filterDate}_{$jadwalName}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
