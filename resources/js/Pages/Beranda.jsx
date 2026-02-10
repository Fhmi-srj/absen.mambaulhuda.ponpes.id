import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { StatSkeleton, CardSkeleton, TableSkeleton } from '../Components/Skeleton';
import LoadingSpinner from '../Components/LoadingSpinner';

export default function Beranda() {
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState({
        siswaCount: 0,
        presentToday: 0,
        aktivitasToday: 0,
        userCount: 0,
        lateSiswa: [],
        recentAktivitas: [],
    });

    useEffect(() => {
        document.title = 'Dashboard - Aktivitas Santri';
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const response = await fetch('/api/dashboard/stats');
            if (response.ok) {
                const result = await response.json();
                setData(result);
            }
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <div className="min-h-[70vh] flex items-center justify-center">
                <LoadingSpinner size="large" text="Menyiapkan Ringkasan..." />
            </div>
        );
    }

    const stats = [
        { value: data.siswaCount || 0, label: 'Total Santri', icon: 'fa-user-graduate', color: 'bg-blue-100 text-blue-500' },
        { value: data.presentToday || 0, label: 'Hadir Hari Ini', icon: 'fa-check-circle', color: 'bg-green-100 text-green-500' },
        { value: data.aktivitasToday || 0, label: 'Aktivitas Hari Ini', icon: 'fa-clipboard-list', color: 'bg-amber-100 text-amber-500' },
        { value: data.userCount || 0, label: 'Total User', icon: 'fa-users', color: 'bg-blue-100 text-blue-500' },
    ];

    return (
        <>

            {/* Stats Cards */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                {stats.map((stat, i) => (
                    <div key={i} className="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow text-center">
                        <div className="text-3xl font-bold text-gray-800 mb-1">{stat.value}</div>
                        <div className="text-sm text-gray-500 mb-3">{stat.label}</div>
                        <div className={`w-11 h-11 ${stat.color} rounded-xl flex items-center justify-center mx-auto`}>
                            <i className={`fas ${stat.icon}`}></i>
                        </div>
                    </div>
                ))}
            </div>

            <div className="grid lg:grid-cols-3 gap-6 mb-6">
                {/* Chart */}
                <div className="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
                    <div className="px-5 py-4 border-b border-gray-100/50 flex items-center gap-2">
                        <i className="fas fa-chart-bar text-blue-500"></i>
                        <span className="font-semibold text-gray-800">Grafik Kehadiran 7 Hari Terakhir</span>
                    </div>
                    <div className="p-5">
                        <div id="attendanceChart" className="h-[300px] flex items-center justify-center text-gray-400">
                            <span>Chart akan ditampilkan di sini</span>
                        </div>
                    </div>
                </div>

                {/* Late Students */}
                <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div className="px-5 py-4 border-b border-gray-100/50 flex items-center gap-2">
                        <i className="fas fa-clock text-amber-500"></i>
                        <span className="font-semibold text-gray-800">Terlambat Hari Ini</span>
                    </div>
                    <div className="p-5">
                        {data.lateSiswa && data.lateSiswa.length > 0 ? (
                            <div className="space-y-4">
                                {data.lateSiswa.map((late, i) => (
                                    <div key={i} className="flex items-center gap-3 pb-4 border-b border-gray-100/50 last:border-0">
                                        <div className="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                            <i className="fas fa-user text-amber-500"></i>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <div className="font-semibold text-gray-800 truncate">{late.nama_lengkap}</div>
                                            <div className="text-sm text-gray-500 truncate">{late.kelas}</div>
                                        </div>
                                        <span className="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded flex-shrink-0">
                                            {late.minutes_late || 0} menit
                                        </span>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-8">
                                <i className="fas fa-check-circle text-5xl text-green-500 mb-3"></i>
                                <p className="text-gray-500">Tidak ada siswa terlambat</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Recent Activities */}
            <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                <div className="px-5 py-4 border-b border-gray-100/50 flex items-center justify-between">
                    <div className="flex items-center gap-2">
                        <i className="fas fa-history text-blue-500"></i>
                        <span className="font-semibold text-gray-800">Aktivitas Terbaru</span>
                    </div>
                    <Link to="/aktivitas" className="text-sm text-blue-500 hover:text-blue-600 font-medium">
                        Lihat Semua
                    </Link>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Waktu</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Santri</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Kategori</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Judul</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100/50">
                            {data.recentAktivitas && data.recentAktivitas.length > 0 ? (
                                data.recentAktivitas.map((akt, i) => (
                                    <tr key={i} className="hover:bg-gray-50">
                                        <td className="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">{akt.tanggal_formatted || '-'}</td>
                                        <td className="px-5 py-4 whitespace-nowrap">
                                            <div className="font-medium text-gray-800">{akt.santri?.nama_lengkap || akt.nama_lengkap || '-'}</div>
                                            <div className="text-sm text-gray-500">{akt.santri?.kelas || akt.kelas || '-'}</div>
                                        </td>
                                        <td className="px-5 py-4 whitespace-nowrap">
                                            <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${akt.kategori === 'sakit' ? 'bg-red-100 text-red-500' :
                                                    akt.kategori === 'izin_keluar' ? 'bg-amber-100 text-amber-500' :
                                                        akt.kategori === 'izin_pulang' ? 'bg-orange-100 text-orange-500' :
                                                            akt.kategori === 'sambangan' ? 'bg-emerald-100 text-emerald-500' :
                                                                akt.kategori === 'pelanggaran' ? 'bg-pink-100 text-pink-500' :
                                                                    'bg-blue-100 text-blue-500'
                                                }`}>
                                                {(akt.kategori || '').replace('_', ' ')}
                                            </span>
                                        </td>
                                        <td className="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">{akt.judul || '-'}</td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="4" className="px-5 py-8 text-center text-gray-500">
                                        Belum ada aktivitas
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}
