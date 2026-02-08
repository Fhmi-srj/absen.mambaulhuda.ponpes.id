@echo off
title ══════ PRINT SERVER PONDOK ══════
color 0A

echo.
echo  ╔═══════════════════════════════════════════════╗
echo  ║                                               ║
echo  ║       MAMBAUL HUDA PRINT SERVER               ║
echo  ║                                               ║
echo  ║       Menunggu antrian cetak slip...          ║
echo  ║       Tekan Ctrl+C untuk berhenti             ║
echo  ║                                               ║
echo  ╚═══════════════════════════════════════════════╝
echo.

cd /d "%~dp0"

REM Coba berbagai lokasi PHP
if exist "C:\laragon\bin\php\php-8.3.1-Win32-vs16-x64\php.exe" (
    "C:\laragon\bin\php\php-8.3.1-Win32-vs16-x64\php.exe" artisan print:server
    goto end
)

if exist "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe" (
    "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe" artisan print:server
    goto end
)

if exist "C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64\php.exe" (
    "C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64\php.exe" artisan print:server
    goto end
)

REM Fallback ke php di PATH
php artisan print:server

:end
echo.
echo  ══════════════════════════════════════════════
echo  Print server berhenti. Tekan tombol apapun...
echo  ══════════════════════════════════════════════
pause >nul
