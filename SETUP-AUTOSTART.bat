@echo off
title SETUP AUTO-START PRINT SERVER
color 0B

echo.
echo  ╔═══════════════════════════════════════════════╗
echo  ║                                               ║
echo  ║   SETUP AUTO-START PRINT SERVER               ║
echo  ║                                               ║
echo  ║   Script ini akan mendaftarkan print server    ║
echo  ║   agar otomatis jalan saat komputer menyala   ║
echo  ║                                               ║
echo  ╚═══════════════════════════════════════════════╝
echo.

set "SCRIPT_DIR=%~dp0"
set "VBS_PATH=%SCRIPT_DIR%START-PRINT-SERVER-BG.vbs"

REM Buat shortcut di folder Startup
set "STARTUP_DIR=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"
set "SHORTCUT=%STARTUP_DIR%\PrintServerPondok.lnk"

echo  Membuat shortcut di folder Startup...

powershell -Command "$ws = New-Object -ComObject WScript.Shell; $s = $ws.CreateShortcut('%SHORTCUT%'); $s.TargetPath = 'wscript.exe'; $s.Arguments = '\"%VBS_PATH%\"'; $s.WorkingDirectory = '%SCRIPT_DIR%'; $s.Description = 'Mambaul Huda Print Server'; $s.Save()"

if exist "%SHORTCUT%" (
    echo.
    echo  ✓ Auto-start berhasil didaftarkan!
    echo.
    echo  Print server akan otomatis berjalan setiap kali
    echo  komputer dinyalakan dan user login.
    echo.
    echo  Untuk membatalkan auto-start, hapus file:
    echo  %SHORTCUT%
) else (
    echo.
    echo  ✗ Gagal membuat shortcut auto-start.
    echo  Silakan salin file START-PRINT-SERVER-BG.vbs
    echo  ke folder Startup secara manual:
    echo  %STARTUP_DIR%
)

echo.
echo  ══════════════════════════════════════════════
echo  Tekan tombol apapun untuk menutup...
echo  ══════════════════════════════════════════════
pause >nul
