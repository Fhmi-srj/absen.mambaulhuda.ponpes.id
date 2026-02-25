import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import ProtectedRoute from './Components/ProtectedRoute';
import AppLayout from './Layouts/AppLayout';
import Login from './Pages/Auth/Login';
import Beranda from './Pages/Beranda';
import Aktivitas from './Pages/Aktivitas';
import Profil from './Pages/Profil';
import Pemindai from './Pages/Pemindai';
import Riwayat from './Pages/Riwayat';

// User Pages
import AbsensiLangsung from './Pages/AbsensiLangsung';
import DaftarRfid from './Pages/DaftarRfid';
import Menu from './Pages/Menu';

// Admin Pages
import AdminPengguna from './Pages/Admin/Pengguna';
import AdminSantri from './Pages/Admin/Santri';
import AdminJadwal from './Pages/Admin/Jadwal';
import AdminAbsensiManual from './Pages/Admin/AbsensiManual';
import AdminLaporan from './Pages/Admin/Laporan';
import AdminLogAktivitas from './Pages/Admin/LogAktivitas';
import AdminTrash from './Pages/Admin/Trash';
import AdminPengaturan from './Pages/Admin/Pengaturan';
import AdminSantriImport from './Pages/Admin/SantriImport';
import Kiosk from './Pages/Kiosk';
import KonfirmasiKembali from './Pages/KonfirmasiKembali';
import PrintServer from './Pages/PrintServer';
import CetakKartu from './Pages/CetakKartu';
import KartuQr from './Pages/KartuQr';

function App() {
    return (
        <AuthProvider>
            <Routes>
                {/* Public Route */}
                <Route path="/login" element={<Login />} />

                <Route path="/konfirmasi-kembali" element={<KonfirmasiKembali />} />
                <Route path="/print-server" element={<PrintServer />} />
                <Route path="/cetak-kartu" element={<CetakKartu />} />
                <Route path="/kartu-qr/:id" element={<KartuQr />} />

                {/* Protected Routes */}
                <Route
                    path="/*"
                    element={
                        <ProtectedRoute>
                            <AppLayout>
                                <Routes>
                                    <Route path="/" element={<Navigate to="/beranda" replace />} />
                                    <Route path="/beranda" element={<Beranda />} />
                                    <Route path="/aktivitas" element={<Aktivitas />} />
                                    <Route path="/profil" element={<Profil />} />
                                    <Route path="/pemindai" element={<Pemindai />} />
                                    <Route path="/riwayat" element={<Riwayat />} />
                                    <Route path="/menu" element={<Menu />} />
                                    <Route path="/kios" element={<Kiosk />} />

                                    {/* User Routes */}
                                    <Route path="/absensi-langsung" element={<AbsensiLangsung />} />
                                    <Route path="/daftar-rfid" element={<DaftarRfid />} />

                                    {/* Admin Routes */}
                                    <Route path="/admin/pengguna" element={<AdminPengguna />} />
                                    <Route path="/admin/santri" element={<AdminSantri />} />
                                    <Route path="/admin/jadwal" element={<AdminJadwal />} />
                                    <Route path="/admin/absensi-manual" element={<AdminAbsensiManual />} />
                                    <Route path="/admin/laporan" element={<AdminLaporan />} />
                                    <Route path="/admin/log-aktivitas" element={<AdminLogAktivitas />} />
                                    <Route path="/admin/trash" element={<AdminTrash />} />
                                    <Route path="/admin/pengaturan" element={<AdminPengaturan />} />
                                    <Route path="/admin/santri-import" element={<AdminSantriImport />} />
                                </Routes>
                            </AppLayout>
                        </ProtectedRoute>
                    }
                />
            </Routes>
        </AuthProvider>
    );
}

export default App;


