<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PemindaiController;
use App\Http\Controllers\DaftarRfidController;
use App\Http\Controllers\AbsensiLangsungController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\CetakKartuController;
use App\Http\Controllers\KartuQrController;
use App\Http\Controllers\KiosController;
use App\Http\Controllers\PrintIzinController;
use App\Http\Controllers\PrintServerController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\RfidApiController;
use App\Http\Controllers\Api\AktivitasApiController;
use App\Http\Controllers\Api\SantriApiController;
use App\Http\Controllers\Api\PrintIzinApiController;
use App\Http\Controllers\Api\PrintQueueApiController;
use App\Http\Controllers\KonfirmasiKembaliController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Routes

// RFID Kiosk - Public page (no login required)
Route::get('/kios', [KiosController::class, 'index'])->name('kios');

// Print Server - Public page (no login required)
Route::get('/print-server', [PrintServerController::class, 'index'])->name('print-server');

// Konfirmasi Kembali - Public page (no login required)
Route::get('/konfirmasi-kembali', [KonfirmasiKembaliController::class, 'index'])->name('konfirmasi-kembali');
Route::get('/api/public/santri-izin-aktif', [KonfirmasiKembaliController::class, 'getSantriAktif']);
Route::get('/api/public/izin/{id}', [KonfirmasiKembaliController::class, 'getDetail']);
Route::post('/api/public/konfirmasi-kembali', [KonfirmasiKembaliController::class, 'konfirmasi']);
Route::post('/api/public/konfirmasi/search', [KonfirmasiKembaliController::class, 'searchByKode']);
Route::post('/api/public/konfirmasi/direct', [KonfirmasiKembaliController::class, 'konfirmasiDirect']);

// Kiosk Manual Attendance - Public (no login required)
Route::get('/api/public/santri/search', [\App\Http\Controllers\Api\SantriApiController::class, 'search']);
Route::post('/api/public/attendance/manual', [\App\Http\Controllers\Api\AttendanceApiController::class, 'manualKiosk']);
Route::get('/api/public/kios/roster', [\App\Http\Controllers\KiosController::class, 'roster']);

// Guest routes - SPA will handle login UI
Route::middleware('guest')->group(function () {
    // SPA handles login page
    Route::get('/login', fn() => view('spa'))->name('login');
    Route::get('/masuk', fn() => view('spa'))->name('masuk');
    // Keep POST login for API
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/keluar', [AuthController::class, 'logout'])->name('keluar');

    // SPA routes - serve SPA template for client-side routing
    Route::get('/', fn() => view('spa'));
    Route::get('/beranda', fn() => view('spa'))->name('beranda');
    Route::get('/aktivitas', fn() => view('spa'))->name('aktivitas');
    Route::get('/profil', fn() => view('spa'))->name('profil');
        Route::get('/api/profil', [ProfilController::class, 'index'])->name('profil.api');
        Route::post('/profil/update-data', [ProfilController::class, 'updateData'])->name('profil.update-data');
        Route::post('/profil/update-password', [ProfilController::class, 'updatePassword'])->name('profil.update-password');
        Route::post('/profil/update-foto', [ProfilController::class, 'updateFoto'])->name('profil.update-foto');
    Route::get('/pemindai', fn() => view('spa'))->name('pemindai');
    Route::get('/riwayat', fn() => view('spa'))->name('riwayat');
    Route::get('/absensi-langsung', fn() => view('spa'))->name('absensi-langsung');
    Route::get('/daftar-rfid', fn() => view('spa'))->name('daftar-rfid');
    Route::get('/print-izin', fn() => view('spa'))->name('print-izin');
    Route::get('/cetak-kartu', [CetakKartuController::class, 'index'])->name('cetak-kartu');
    Route::get('/kartu-qr/{id}', [KartuQrController::class, 'show'])->name('kartu-qr');

    // API routes
    Route::prefix('api')->group(function () {

        // Dashboard
        Route::get('/dashboard/stats', [\App\Http\Controllers\Api\DashboardController::class, 'stats'])->name('api.dashboard.stats');

        // Riwayat
        Route::get('/riwayat', [\App\Http\Controllers\Api\RiwayatController::class, 'index'])->name('api.riwayat');

        // Santri search
        Route::get('/santri/search', [SantriApiController::class, 'search'])->name('api.santri.search');
        Route::get('/santri/{id}', [SantriApiController::class, 'show'])->name('api.santri.show');

        // Aktivitas CRUD
        Route::post('/aktivitas/data', [AktivitasApiController::class, 'data'])->name('api.aktivitas.data');
        Route::post('/aktivitas/store', [AktivitasApiController::class, 'store'])->name('api.aktivitas.store');
        Route::get('/aktivitas/{id}/edit', [AktivitasApiController::class, 'edit'])->name('api.aktivitas.edit');
        Route::post('/aktivitas/{id}/update', [AktivitasApiController::class, 'update'])->name('api.aktivitas.update');
        Route::delete('/aktivitas/{id}', [AktivitasApiController::class, 'destroy'])->name('api.aktivitas.destroy');
        Route::post('/aktivitas/bulk-delete', [AktivitasApiController::class, 'bulkDestroy'])->name('api.aktivitas.bulk-delete');

        // WhatsApp API
        Route::post('/kirim-wa', [\App\Http\Controllers\Api\WhatsAppApiController::class, 'send'])->name('api.kirim-wa');

        // Attendance API
        Route::post('/attendance/store', [AttendanceApiController::class, 'store'])->name('api.attendance.store');
        Route::get('/attendance/today', [AttendanceApiController::class, 'today'])->name('api.attendance.today');
        Route::post('/attendance/rfid', [AttendanceApiController::class, 'rfid'])->name('api.attendance.rfid');

        // RFID API
        Route::get('/rfid/check', [RfidApiController::class, 'check'])->name('api.rfid.check');
        Route::post('/rfid/register', [RfidApiController::class, 'register'])->name('api.rfid.register');
        Route::post('/rfid/unregister', [RfidApiController::class, 'unregister'])->name('api.rfid.unregister');

        // Live Attendance API
        Route::get('/live-attendance', [\App\Http\Controllers\Api\LiveAttendanceApiController::class, 'index'])->name('api.live-attendance');

        // Print Izin API
        Route::get('/print-izin', [PrintIzinApiController::class, 'getSantri'])->name('api.print-izin');
        Route::post('/print-izin', [PrintIzinApiController::class, 'store'])->name('api.print-izin.store');

        // Print Queue API
        Route::post('/print-queue', [PrintQueueApiController::class, 'store'])->name('api.print-queue.store');
        Route::get('/print-queue/stats', [PrintQueueApiController::class, 'stats'])->name('api.print-queue.stats');
        Route::get('/print-queue/pending', [PrintQueueApiController::class, 'pending'])->name('api.print-queue.pending');
        Route::post('/print-queue/{id}/processing', [PrintQueueApiController::class, 'processing'])->name('api.print-queue.processing');
        Route::post('/print-queue/{id}/complete', [PrintQueueApiController::class, 'complete'])->name('api.print-queue.complete');
        Route::post('/print-queue/{id}/fail', [PrintQueueApiController::class, 'fail'])->name('api.print-queue.fail');

        // Print Server Status
        Route::get('/print-server/status', [\App\Http\Controllers\PrintServerStatusController::class, 'status'])->name('api.print-server.status');

        // Admin APIs
        Route::prefix('admin')->group(function () {
            // Pengguna API
            Route::post('/pengguna', [\App\Http\Controllers\Api\Admin\PenggunaApiController::class, 'store'])->name('api.admin.pengguna.store');
            Route::post('/pengguna/{id}', [\App\Http\Controllers\Api\Admin\PenggunaApiController::class, 'update'])->name('api.admin.pengguna.update');
            Route::delete('/pengguna/{id}', [\App\Http\Controllers\Api\Admin\PenggunaApiController::class, 'destroy'])->name('api.admin.pengguna.destroy');
            Route::post('/pengguna/{id}/reset-device', [\App\Http\Controllers\Api\Admin\PenggunaApiController::class, 'resetDevice'])->name('api.admin.pengguna.reset-device');

            // Santri API
            Route::get('/santri/{id}', [\App\Http\Controllers\Api\Admin\SantriApiController::class, 'edit'])->name('api.admin.santri.edit');
            Route::post('/santri', [\App\Http\Controllers\Api\Admin\SantriApiController::class, 'store'])->name('api.admin.santri.store');
            Route::post('/santri/{id}', [\App\Http\Controllers\Api\Admin\SantriApiController::class, 'update'])->name('api.admin.santri.update');
            Route::delete('/santri/{id}', [\App\Http\Controllers\Api\Admin\SantriApiController::class, 'destroy'])->name('api.admin.santri.destroy');

            // Jadwal API
            Route::post('/jadwal', [\App\Http\Controllers\Api\Admin\JadwalApiController::class, 'store'])->name('api.admin.jadwal.store');
            Route::post('/jadwal/{id}', [\App\Http\Controllers\Api\Admin\JadwalApiController::class, 'update'])->name('api.admin.jadwal.update');
            Route::delete('/jadwal/{id}', [\App\Http\Controllers\Api\Admin\JadwalApiController::class, 'destroy'])->name('api.admin.jadwal.destroy');
        });
    });

    // Admin pages (khusus admin)
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/pengguna', [\App\Http\Controllers\Admin\PenggunaController::class, 'index'])->name('admin.pengguna');
        Route::get('/santri', [\App\Http\Controllers\Admin\SantriController::class, 'index'])->name('admin.santri');
        Route::get('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('admin.jadwal');
        Route::get('/riwayat/export', [\App\Http\Controllers\Api\RiwayatController::class, 'export'])->name('api.riwayat.export');
        Route::get('/riwayat/export-pdf', [\App\Http\Controllers\Api\RiwayatController::class, 'exportPdf'])->name('api.riwayat.export-pdf');

        // Absensi Manual
        Route::get('/absensi-manual', [\App\Http\Controllers\Admin\AbsensiManualController::class, 'index'])->name('admin.absensi-manual');
        Route::post('/absensi-manual', [\App\Http\Controllers\Admin\AbsensiManualController::class, 'store'])->name('admin.absensi-manual.store');
        Route::delete('/absensi-manual/{id}', [\App\Http\Controllers\Admin\AbsensiManualController::class, 'destroy'])->name('admin.absensi-manual.destroy');

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan');

        // Log Aktivitas
        Route::get('/log-aktivitas', [\App\Http\Controllers\Admin\LogAktivitasController::class, 'index'])->name('admin.log-aktivitas');
        Route::post('/log-aktivitas/bulk-delete', [\App\Http\Controllers\Admin\LogAktivitasController::class, 'bulkDelete'])->name('admin.log-aktivitas.bulk-delete');
        Route::get('/log-aktivitas/{id}/delete', [\App\Http\Controllers\Admin\LogAktivitasController::class, 'deleteSingle'])->name('admin.log-aktivitas.delete-single');
        Route::get('/log-aktivitas/clear-all', [\App\Http\Controllers\Admin\LogAktivitasController::class, 'clearAll'])->name('admin.log-aktivitas.clear-all');

        // Trash
        Route::get('/trash', [\App\Http\Controllers\Admin\TrashController::class, 'index'])->name('admin.trash');
        Route::post('/trash/restore', [\App\Http\Controllers\Admin\TrashController::class, 'restore'])->name('admin.trash.restore');
        Route::post('/trash/permanent-delete', [\App\Http\Controllers\Admin\TrashController::class, 'permanentDelete'])->name('admin.trash.permanent-delete');
        Route::post('/trash/bulk-restore', [\App\Http\Controllers\Admin\TrashController::class, 'bulkRestore'])->name('admin.trash.bulk-restore');
        Route::post('/trash/bulk-delete', [\App\Http\Controllers\Admin\TrashController::class, 'bulkDelete'])->name('admin.trash.bulk-delete');
        Route::post('/trash/empty', [\App\Http\Controllers\Admin\TrashController::class, 'emptyTrash'])->name('admin.trash.empty');
        Route::post('/trash/settings', [\App\Http\Controllers\Admin\TrashController::class, 'saveSettings'])->name('admin.trash.settings');

        // Pengaturan
        Route::get('/pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'index'])->name('admin.pengaturan');
        Route::post('/pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'update'])->name('admin.pengaturan.update');

        // Santri Import
        Route::get('/santri-import', [\App\Http\Controllers\Admin\SantriImportController::class, 'index'])->name('admin.santri-import');
        Route::post('/santri-import', [\App\Http\Controllers\Admin\SantriImportController::class, 'import'])->name('admin.santri-import.import');
    });
});

// SPA Catch-all route - must be last!
// This serves the React SPA for all client-side routes
Route::get('/{any}', function () {
    return view('spa');
})->where('any', '^(?!api|kios|print-server|konfirmasi-kembali|linkstorage|clear-cache|uploads|storage|img|css|js|fonts|favicon).*$');

