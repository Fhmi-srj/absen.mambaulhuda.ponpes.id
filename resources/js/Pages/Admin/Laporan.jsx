import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function AdminLaporan() {
    const [isLoading, setIsLoading] = useState(true);
    const [siswaList, setSiswaList] = useState([]);
    const [jadwalList, setJadwalList] = useState([]);
    const [attendances, setAttendances] = useState([]);
    const [stats, setStats] = useState({ total: 0, hadir: 0, terlambat: 0, tidak_hadir: 0 });

    const [filters, setFilters] = useState({
        date_from: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0],
        date_to: new Date().toISOString().split('T')[0],
        siswa_id: '',
        jadwal_id: '',
        status: ''
    });

    useEffect(() => {
        document.title = 'Laporan Absensi - Admin';
        fetchData();
    }, []);

    const fetchData = async (currentFilters = filters) => {
        setIsLoading(true);
        try {
            const queryParams = new URLSearchParams(currentFilters).toString();
            const response = await fetch(`/admin/laporan?${queryParams}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            setSiswaList(data.siswaList);
            setJadwalList(data.jadwalList);
            setAttendances(data.attendances);
            setStats(data.stats);
        } catch (error) {
            console.error('Error fetching data:', error);
            Swal.fire('Error', 'Gagal mengambil data laporan', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prev => ({ ...prev, [name]: value }));
    };

    const handleFilterSubmit = (e) => {
        e.preventDefault();
        fetchData();
    };

    const handleExport = () => {
        const queryParams = new URLSearchParams({ ...filters, export: 'excel' }).toString();
        window.location.href = `/admin/laporan?${queryParams}`;
    };

    return (
        <div className="pb-24">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div className="flex items-center gap-3">
                    <div className="bg-indigo-500 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i className="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <h5 className="font-bold text-gray-800 mb-0">Laporan Absensi</h5>
                        <p className="text-xs text-gray-500">Filter dan cetak laporan absensi santri</p>
                    </div>
                </div>

                <button
                    onClick={handleExport}
                    className="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm shadow-sm hover:bg-emerald-600 active:scale-95 transition-all"
                >
                    <i className="fas fa-file-excel"></i>
                    Export Excel
                </button>
            </div>

            {/* Filter Card */}
            <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
                <form onSubmit={handleFilterSubmit} className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
                        <input
                            type="date"
                            name="date_from"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.date_from}
                            onChange={handleFilterChange}
                        />
                    </div>
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                        <input
                            type="date"
                            name="date_to"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.date_to}
                            onChange={handleFilterChange}
                        />
                    </div>
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Siswa</label>
                        <select
                            name="siswa_id"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.siswa_id}
                            onChange={handleFilterChange}
                        >
                            <option value="">Semua Siswa</option>
                            {siswaList.map(s => (
                                <option key={s.id} value={s.id}>{s.nama_lengkap}</option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Jadwal</label>
                        <select
                            name="jadwal_id"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.jadwal_id}
                            onChange={handleFilterChange}
                        >
                            <option value="">Semua Jadwal</option>
                            {jadwalList.map(j => (
                                <option key={j.id} value={j.id}>{j.name}</option>
                            ))}
                        </select>
                    </div>
                    <div className="flex gap-2">
                        <div className="flex-1">
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                            <select
                                name="status"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                                value={filters.status}
                                onChange={handleFilterChange}
                            >
                                <option value="">Semua Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="absen">Absen</option>
                            </select>
                        </div>
                        <button
                            type="submit"
                            className="px-4 py-2.5 bg-blue-500 text-white rounded-xl shadow-sm hover:bg-blue-600 transition-all flex items-center justify-center"
                        >
                            <i className="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {[
                    { label: 'Total Record', value: stats.total, color: 'text-blue-500', bg: 'bg-blue-50' },
                    { label: 'Hadir', value: stats.hadir, color: 'text-green-500', bg: 'bg-green-50' },
                    { label: 'Terlambat', value: stats.terlambat, color: 'text-amber-500', bg: 'bg-amber-50' },
                    { label: 'Tidak Hadir', value: stats.tidak_hadir, color: 'text-red-500', bg: 'bg-red-50' },
                ].map((stat, idx) => (
                    <div key={idx} className={`${stat.bg} rounded-3xl p-4 border border-white shadow-sm text-center`}>
                        <div className={`text-2xl font-black ${stat.color} mb-1`}>{stat.value || 0}</div>
                        <div className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{stat.label}</div>
                    </div>
                ))}
            </div>

            {/* Table Card */}
            <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50/50">
                            <tr>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Santri</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Jadwal</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Ket.</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {isLoading ? (
                                <tr>
                                    <td colSpan="5" className="px-6 py-12">
                                        <div className="flex flex-col items-center justify-center text-gray-400">
                                            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-2"></div>
                                            <span className="text-sm font-medium">Memuat data...</span>
                                        </div>
                                    </td>
                                </tr>
                            ) : attendances.length === 0 ? (
                                <tr>
                                    <td colSpan="5" className="px-6 py-12 text-center text-gray-400">
                                        <i className="fas fa-folder-open text-4xl mb-3 block opacity-20"></i>
                                        <span className="text-sm font-medium">Tidak ada data untuk filter ini</span>
                                    </td>
                                </tr>
                            ) : (
                                attendances.map((a, idx) => (
                                    <tr key={idx} className="hover:bg-gray-50/50 transition-colors">
                                        <td className="px-6 py-4">
                                            <div className="font-bold text-gray-700 whitespace-nowrap">
                                                {new Date(a.attendance_date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' })}
                                            </div>
                                            <div className="text-xs text-gray-400 font-medium">
                                                {a.attendance_time.substring(0, 5)}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="font-bold text-gray-700 leading-tight mb-0.5">{a.nama_lengkap}</div>
                                            <div className="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{a.kelas} | {a.nomor_induk}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="inline-block px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px] font-black uppercase tracking-wider">
                                                {a.jadwal_name || '-'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={`px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${a.status === 'hadir' ? 'bg-green-100 text-green-600' :
                                                    a.status === 'terlambat' ? 'bg-amber-100 text-amber-600' :
                                                        'bg-red-100 text-red-600'
                                                }`}>
                                                {a.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-xs text-gray-500 line-clamp-1 max-w-[120px]" title={a.notes}>
                                                {a.minutes_late ? `${a.minutes_late}m ` : ''}
                                                {a.notes || '-'}
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
