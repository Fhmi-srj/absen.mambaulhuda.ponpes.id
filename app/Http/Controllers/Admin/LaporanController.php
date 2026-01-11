<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));
        $filterSiswa = $request->get('siswa_id', '');
        $filterJadwal = $request->get('jadwal_id', '');
        $filterStatus = $request->get('status', '');

        $siswaList = DataInduk::whereNull('deleted_at')
            ->orderBy('nama_lengkap')
            ->select('id', 'nama_lengkap')
            ->get();

        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time')
            ->get();

        $query = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->whereBetween('a.attendance_date', [$dateFrom, $dateTo])
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name');

        if ($filterSiswa)
            $query->where('a.user_id', $filterSiswa);
        if ($filterJadwal)
            $query->where('a.jadwal_id', $filterJadwal);
        if ($filterStatus)
            $query->where('a.status', $filterStatus);

        $attendances = $query->orderBy('a.attendance_date', 'desc')
            ->orderBy('a.attendance_time', 'desc')
            ->get();

        // Stats
        $statsQuery = DB::table('attendances as a')
            ->whereNull('a.deleted_at')
            ->whereBetween('a.attendance_date', [$dateFrom, $dateTo]);
        if ($filterSiswa)
            $statsQuery->where('a.user_id', $filterSiswa);

        $stats = $statsQuery->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) as terlambat,
            SUM(CASE WHEN status IN ('absen', 'sakit', 'izin') THEN 1 ELSE 0 END) as tidak_hadir
        ")->first();

        // Export Excel
        if ($request->get('export') === 'excel') {
            return $this->exportExcel($attendances, $dateFrom, $dateTo);
        }

        return view('admin.laporan', compact(
            'siswaList',
            'jadwalList',
            'attendances',
            'stats',
            'dateFrom',
            'dateTo',
            'filterSiswa',
            'filterJadwal',
            'filterStatus'
        ));
    }

    private function exportExcel($attendances, $dateFrom, $dateTo)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Absensi');

        $headers = ['No', 'Tanggal', 'Waktu', 'NISN', 'Nama Siswa', 'Kelas', 'Jadwal', 'Status', 'Terlambat (menit)', 'Catatan'];
        $sheet->fromArray($headers, null, 'A1');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(25);

        $row = 2;
        $no = 1;
        foreach ($attendances as $a) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($a->attendance_date)));
            $sheet->setCellValue('C' . $row, substr($a->attendance_time, 0, 5));
            $sheet->setCellValue('D' . $row, $a->nomor_induk ?? '-');
            $sheet->setCellValue('E' . $row, $a->nama_lengkap);
            $sheet->setCellValue('F' . $row, $a->kelas ?? '-');
            $sheet->setCellValue('G' . $row, $a->jadwal_name ?? '-');
            $sheet->setCellValue('H' . $row, ucfirst($a->status));
            $sheet->setCellValue('I' . $row, $a->minutes_late ?? 0);
            $sheet->setCellValue('J' . $row, $a->notes ?? '');

            $statusColor = match ($a->status) {
                'hadir' => 'C6EFCE',
                'terlambat' => 'FFEB9C',
                'absen', 'izin', 'sakit' => 'FFC7CE',
                default => 'FFFFFF'
            };
            $sheet->getStyle('H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($statusColor);
            $row++;
            $no++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if ($row > 2) {
            $sheet->getStyle('A1:J' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $filename = 'laporan_absensi_' . $dateFrom . '_' . $dateTo . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
