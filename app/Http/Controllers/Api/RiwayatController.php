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

        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time')
            ->get();

        $query = DB::table('attendances as a')
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

        $attendances = $query->orderBy('a.attendance_time', 'desc')->get();

        $totalHadir = $attendances->where('status', 'hadir')->count();
        $totalTerlambat = $attendances->where('status', 'terlambat')->count();
        $totalAbsen = $attendances->where('status', 'absen')->count();

        return response()->json([
            'jadwalList' => $jadwalList,
            'attendances' => $attendances,
            'filterDate' => $filterDate,
            'filterJadwal' => $filterJadwal,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalAbsen' => $totalAbsen,
        ]);
    }

    public function export(Request $request)
    {
        $filterDate = $request->get('date', date('Y-m-d'));
        $filterJadwal = $request->get('jadwal', '');

        $query = DB::table('attendances as a')
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

        $attendances = $query->orderBy('a.attendance_time', 'asc')->get();

        $jadwalName = $filterJadwal
            ? (JadwalAbsen::find($filterJadwal)?->name ?? 'Semua')
            : 'Semua';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Kehadiran');

        // Title and Info Rows
        $sheet->setCellValue('A1', 'LAPORAN RIWAYAT KEHADIRAN');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal: ' . $filterDate);
        $sheet->setCellValue('A3', 'Jadwal: ' . $jadwalName);

        // Header Row
        $headers = ['No', 'Waktu', 'Nama Siswa', 'Kelas', 'Nomor Induk', 'Jadwal', 'Status', 'Terlambat (menit)', 'Catatan'];
        $sheet->fromArray($headers, null, 'A5');

        // Header Styling
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
                $sheet->getStyle('G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($statusColor);
            }

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Borders for all data
        if ($row > 6) {
            $sheet->getStyle('A5:I' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $filename = "Riwayat_Kehadiran_{$filterDate}_{$jadwalName}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
