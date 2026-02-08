<?php

/**
 * Local Print Server - Aktivitas
 * 
 * Script PHP CLI untuk mencetak slip otomatis ke printer POS-58
 * Jalankan via: php artisan print:server
 * 
 * TANPA browser, TANPA QZ Tray, TANPA konfirmasi
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrintServer extends Command
{
    protected $signature = 'print:server {--printer=POS-58 : Nama printer} {--interval=1 : Interval polling (detik)}';
    protected $description = 'Jalankan print server untuk mencetak slip otomatis';

    private $printerName = 'POS-58';
    private $pollInterval = 1;

    public function handle()
    {
        $this->printerName = $this->option('printer');
        $this->pollInterval = (int) $this->option('interval');

        $this->info("╔═══════════════════════════════════════╗");
        $this->info("║     AKTIVITAS LOCAL PRINT SERVER      ║");
        $this->info("╠═══════════════════════════════════════╣");
        $this->info("║  Printer : {$this->printerName}");
        $this->info("║  Interval: {$this->pollInterval} detik");
        $this->info("║  Tekan Ctrl+C untuk berhenti          ║");
        $this->info("╚═══════════════════════════════════════╝");
        $this->newLine();

        // Test printer connection
        if (!$this->testPrinter()) {
            $this->error("Printer '{$this->printerName}' tidak ditemukan!");
            return 1;
        }

        $this->info("✓ Printer terhubung. Menunggu antrian...");
        $this->newLine();

        // Main loop
        while (true) {
            $this->pollAndProcess();
            sleep($this->pollInterval);
        }

        return 0;
    }

    private function testPrinter(): bool
    {
        // Check if printer exists in Windows
        $output = [];
        exec("powershell -Command \"Get-Printer -Name '{$this->printerName}' -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Name\"", $output);
        return !empty($output) && trim($output[0]) === $this->printerName;
    }

    private function pollAndProcess()
    {
        $jobs = DB::table('print_queue')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        foreach ($jobs as $job) {
            $this->processJob($job);
        }
    }

    private function processJob($job)
    {
        $this->line("[" . now()->format('H:i:s') . "] Memproses job #{$job->id}: {$job->job_type}");

        // Mark as processing
        DB::table('print_queue')->where('id', $job->id)->update(['status' => 'processing']);

        try {
            $data = json_decode($job->job_data, true);
            
            if ($job->job_type === 'slip_konfirmasi') {
                $escpos = $this->generateSlipKonfirmasi($data);
            } elseif ($job->job_type === 'surat_izin') {
                $escpos = $this->generateSuratIzin($data);
            } else {
                throw new \Exception("Unknown job type: {$job->job_type}");
            }

            // Send to printer
            $this->sendToPrinter($escpos);

            // Mark as completed
            DB::table('print_queue')->where('id', $job->id)->update([
                'status' => 'completed',
                'processed_at' => now()
            ]);

            $this->info("  ✓ Job #{$job->id} berhasil dicetak");
        } catch (\Exception $e) {
            DB::table('print_queue')->where('id', $job->id)->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processed_at' => now()
            ]);
            $this->error("  ✗ Job #{$job->id} gagal: " . $e->getMessage());
        }
    }

    private function sendToPrinter(string $data)
    {
        // Use Windows raw print via winspool
        $tempFile = storage_path('app/print_temp_' . uniqid() . '.bin');
        file_put_contents($tempFile, $data);

        // Print using raw copy to printer port
        $psCommand = <<<PS
\$printerName = '{$this->printerName}'
\$port = (Get-Printer -Name \$printerName).PortName
\$content = [System.IO.File]::ReadAllBytes('{$tempFile}')

# Method 1: Try direct to printer via print spooler
try {
    Add-Type -AssemblyName System.Drawing
    \$doc = New-Object System.Drawing.Printing.PrintDocument
    \$doc.PrinterSettings.PrinterName = \$printerName
    
    # Use RawPrinterHelper
\$rawPrinter = @'
using System;
using System.Runtime.InteropServices;

public class RawPrinterHelper
{
    [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Unicode)]
    public class DOCINFOW
    {
        [MarshalAs(UnmanagedType.LPWStr)] public string pDocName;
        [MarshalAs(UnmanagedType.LPWStr)] public string pOutputFile;
        [MarshalAs(UnmanagedType.LPWStr)] public string pDataType;
    }

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool OpenPrinterW(string pPrinterName, out IntPtr phPrinter, IntPtr pDefault);

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool StartDocPrinterW(IntPtr hPrinter, int Level, [In] DOCINFOW pDocInfo);

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool StartPagePrinter(IntPtr hPrinter);

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool WritePrinter(IntPtr hPrinter, IntPtr pBytes, int dwCount, out int dwWritten);

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool EndPagePrinter(IntPtr hPrinter);

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool EndDocPrinter(IntPtr hPrinter);

    [DllImport("winspool.drv", CharSet = CharSet.Unicode, ExactSpelling = true, SetLastError = true)]
    public static extern bool ClosePrinter(IntPtr hPrinter);

    public static bool SendBytesToPrinter(string printerName, byte[] bytes)
    {
        IntPtr hPrinter;
        if (!OpenPrinterW(printerName, out hPrinter, IntPtr.Zero)) return false;

        var di = new DOCINFOW { pDocName = "Raw Document", pDataType = "RAW" };
        if (!StartDocPrinterW(hPrinter, 1, di)) { ClosePrinter(hPrinter); return false; }
        if (!StartPagePrinter(hPrinter)) { EndDocPrinter(hPrinter); ClosePrinter(hPrinter); return false; }

        IntPtr pBytes = Marshal.AllocHGlobal(bytes.Length);
        Marshal.Copy(bytes, 0, pBytes, bytes.Length);
        int written;
        bool success = WritePrinter(hPrinter, pBytes, bytes.Length, out written);
        Marshal.FreeHGlobal(pBytes);

        EndPagePrinter(hPrinter);
        EndDocPrinter(hPrinter);
        ClosePrinter(hPrinter);
        return success;
    }
}
'@
    Add-Type -TypeDefinition \$rawPrinter -Language CSharp
    \$result = [RawPrinterHelper]::SendBytesToPrinter(\$printerName, \$content)
    if (\$result) { exit 0 } else { exit 1 }
} catch {
    Write-Error \$_.Exception.Message
    exit 1
}
PS;

        $psFile = storage_path('app/rawprint_' . uniqid() . '.ps1');
        file_put_contents($psFile, $psCommand);
        
        exec("powershell -ExecutionPolicy Bypass -File \"{$psFile}\" 2>&1", $output, $returnCode);
        
        @unlink($tempFile);
        @unlink($psFile);
        
        if ($returnCode !== 0) {
            throw new \Exception("Raw print failed: " . implode("\n", $output));
        }
    }

    private function generateSlipKonfirmasi(array $data): string
    {
        $ESC = "\x1B";
        $GS = "\x1D";
        
        $kode = $data['kode_konfirmasi'] ?? '000000';
        $nama = mb_substr($data['nama_santri'] ?? '-', 0, 20);
        $kelas = $data['kelas'] ?? '-';
        $judul = mb_substr($data['judul'] ?? '-', 0, 20);
        $batas = isset($data['batas_waktu']) 
            ? date('d/m H:i', strtotime($data['batas_waktu'])) 
            : '-';

        $output = "";
        
        // Reset printer
        $output .= $ESC . "@";
        
        // Center align
        $output .= $ESC . "a\x01";
        
        // Bold + Double height for name
        $output .= $ESC . "E\x01" . $GS . "!\x10";
        $output .= $nama . "\n";
        
        // Normal size
        $output .= $GS . "!\x00" . $ESC . "E\x00";
        $output .= "Kelas {$kelas}\n";
        $output .= "--------------------------------\n";
        
        // Left align
        $output .= $ESC . "a\x00";
        $output .= "Keperluan: {$judul}\n";
        $output .= "Batas    : {$batas}\n";
        $output .= "--------------------------------\n";
        
        // Center align for QR
        $output .= $ESC . "a\x01";
        
        // QR Code (ESC/POS native)
        $output .= $this->generateQRCode($kode);
        
        $output .= "\n";
        
        // Big code text
        $output .= $ESC . "E\x01" . $GS . "!\x11";
        $output .= $kode . "\n";
        
        // Normal
        $output .= $GS . "!\x00" . $ESC . "E\x00";
        $output .= "Kode Konfirmasi\n";
        $output .= "--------------------------------\n";
        $output .= "Scan QR/input kode di:\n";
        $output .= "/konfirmasi-kembali\n\n\n\n";
        
        // Cut paper
        $output .= $GS . "V\x00";
        
        return $output;
    }

    private function generateQRCode(string $data): string
    {
        $GS = "\x1D";
        
        $len = strlen($data) + 3;
        $pL = $len % 256;
        $pH = floor($len / 256);
        
        $qr = "";
        // Model select (model 2)
        $qr .= $GS . "(k\x04\x00\x31\x41\x32\x00";
        // Size (module size 6 - optimal for 58mm paper)  
        $qr .= $GS . "(k\x03\x00\x31\x43\x06";
        // Error correction (M - medium, 15% recovery)
        $qr .= $GS . "(k\x03\x00\x31\x45\x31";
        // Store data
        $qr .= $GS . "(k" . chr($pL) . chr($pH) . "\x31\x50\x30" . $data;
        // Print QR
        $qr .= $GS . "(k\x03\x00\x31\x51\x30";
        
        return $qr;
    }

    private function generateSuratIzin(array $data): string
    {
        $nomor = $data['nomor_surat'] ?? '-';
        return "Surat Izin: {$nomor}\n\n\n\x1DV\x00";
    }
}
