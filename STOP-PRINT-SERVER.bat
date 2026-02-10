@echo off
title STOP PRINT SERVER
color 0C

echo.
echo  ╔═══════════════════════════════════════════════╗
echo  ║                                               ║
echo  ║       MENGHENTIKAN PRINT SERVER...            ║
echo  ║                                               ║
echo  ╚═══════════════════════════════════════════════╝
echo.

REM Cari dan hentikan semua proses php yang menjalankan print:server
tasklist /FI "IMAGENAME eq php.exe" /FO CSV 2>NUL | find /I "php.exe" >NUL
if %ERRORLEVEL%==0 (
    echo  Menghentikan proses print server...
    wmic process where "commandline like '%%print:server%%'" call terminate >NUL 2>&1
    timeout /t 2 /nobreak >NUL
    echo.
    echo  ✓ Print server berhasil dihentikan!
) else (
    echo  Print server tidak sedang berjalan.
)

echo.
echo  ══════════════════════════════════════════════
echo  Tekan tombol apapun untuk menutup...
echo  ══════════════════════════════════════════════
pause >nul
