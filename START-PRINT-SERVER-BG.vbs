' ============================================
' MAMBAUL HUDA - Background Print Server
' ============================================
' Script ini menjalankan print server di latar belakang
' tanpa menampilkan jendela terminal.
'
' Cara pakai: Klik 2x file ini
' Untuk menghentikan: Jalankan STOP-PRINT-SERVER.bat
' ============================================

Dim WshShell, fso, projectDir, phpPath, logFile

Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Tentukan direktori project
projectDir = fso.GetParentFolderName(WScript.ScriptFullName)

' Tentukan file log
logFile = projectDir & "\storage\logs\print-server.log"

' Cari PHP yang tersedia
phpPath = ""

If fso.FileExists("C:\laragon\bin\php\php-8.3.1-Win32-vs16-x64\php.exe") Then
    phpPath = "C:\laragon\bin\php\php-8.3.1-Win32-vs16-x64\php.exe"
ElseIf fso.FileExists("C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe") Then
    phpPath = "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe"
ElseIf fso.FileExists("C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64\php.exe") Then
    phpPath = "C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64\php.exe"
Else
    phpPath = "php"
End If

' Jalankan print server di background (WindowStyle 0 = Hidden)
WshShell.CurrentDirectory = projectDir
WshShell.Run """" & phpPath & """ artisan print:server >> """ & logFile & """ 2>&1", 0, False

' Tampilkan notifikasi
MsgBox "Print Server telah berjalan di latar belakang!" & vbCrLf & vbCrLf & _
       "Log tersimpan di:" & vbCrLf & logFile & vbCrLf & vbCrLf & _
       "Untuk menghentikan, jalankan STOP-PRINT-SERVER.bat", _
       vbInformation, "Print Server - Background Mode"

Set WshShell = Nothing
Set fso = Nothing
