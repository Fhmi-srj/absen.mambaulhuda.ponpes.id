import React, { useState, useEffect } from 'react';
import { PageSkeleton } from '../../Components/Skeleton';

export default function Kehadiran() {
    const [loading, setLoading] = useState(true);
    const [attendances, setAttendances] = useState([]);
    const [jadwalList, setJadwalList] = useState([]);
    const [filterDate, setFilterDate] = useState(new Date().toISOString().split('T')[0]);
    const [filterJadwal, setFilterJadwal] = useState('');
    const [stats, setStats] = useState({ total: 0, hadir: 0, terlambat: 0, absen: 0 });

    useEffect(() => {
        document.title = 'Data Absensi - Aktivitas Santri';
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const params = new URLSearchParams({ date: filterDate, jadwal: filterJadwal });
            const response = await fetch(`/admin/kehadiran?${params}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (response.ok) {
                const data = await response.json();
                setAttendances(data.attendances || []);
                setJadwalList(data.jadwalList || []);
                setStats({
                    total: data.attendances?.length || 0,
                    hadir: data.totalHadir || 0,
                    terlambat: data.totalTerlambat || 0,
                    absen: data.totalAbsen || 0,
                });
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleFilter = (e) => {
        e.preventDefault();
        setLoading(true);
        fetchData();
    };

    const getStatusBadge = (status) => {
        const badges = {
            hadir: 'bg-green-100 text-green-600',
            terlambat: 'bg-amber-100 text-amber-600',
            absen: 'bg-red-100 text-red-600',
            izin: 'bg-blue-100 text-blue-600',
        };
        return badges[status] || 'bg-gray-100 text-gray-600';
    };

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>


            {/* Filters */}
            <div className="bg-white rounded-xl shadow-sm p-4 mb-4">
                <form onSubmit={handleFilter} className="flex flex-wrap gap-3 items-end">
                    <div className="w-40">
                        <label className="block text-xs text-gray-500 mb-1">Tanggal</label>
                        <input type="date" value={filterDate} onChange={(e) => setFilterDate(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
                    </div>
                    <div className="w-40">
                        <label className="block text-xs text-gray-500 mb-1">Jadwal</label>
                        <select value={filterJadwal} onChange={(e) => setFilterJadwal(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua Jadwal</option>
                            {jadwalList.map((j) => (
                                <option key={j.id} value={j.id}>{j.name}</option>
                            ))}
                        </select>
                    </div>
                    <button type="submit" className="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-semibold">
                        <i className="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href={`/admin/kehadiran/export?date=${filterDate}&jadwal=${filterJadwal}`}
                        className="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold inline-flex items-center hover:bg-emerald-600 transition-colors">
                        <i className="fas fa-file-excel mr-1"></i>Export Excel
                    </a>
                </form>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div className="bg-blue-50 rounded-xl p-4 text-center">
                    <div className="text-2xl font-bold text-blue-600">{stats.total}</div>
                    <div className="text-sm text-gray-500">Total Absensi</div>
                </div>
                <div className="bg-green-50 rounded-xl p-4 text-center">
                    <div className="text-2xl font-bold text-green-600">{stats.hadir}</div>
                    <div className="text-sm text-gray-500">Hadir</div>
                </div>
                <div className="bg-amber-50 rounded-xl p-4 text-center">
                    <div className="text-2xl font-bold text-amber-600">{stats.terlambat}</div>
                    <div className="text-sm text-gray-500">Terlambat</div>
                </div>
                <div className="bg-red-50 rounded-xl p-4 text-center">
                    <div className="text-2xl font-bold text-red-600">{stats.absen}</div>
                    <div className="text-sm text-gray-500">Absen</div>
                </div>
            </div>

            {/* Table */}
            <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jadwal</th>
                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Terlambat</th>
                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {attendances.length === 0 ? (
                                <tr>
                                    <td colSpan="6" className="px-4 py-8 text-center text-gray-400">
                                        Tidak ada data absensi
                                    </td>
                                </tr>
                            ) : (
                                attendances.map((a, i) => (
                                    <tr key={i} className="hover:bg-gray-50">
                                        <td className="px-4 py-3 font-semibold">
                                            {a.attendance_time ? new Date('1970-01-01T' + a.attendance_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-'}
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="font-semibold text-gray-800">{a.nama_lengkap}</div>
                                            <small className="text-gray-400">{a.kelas} - {a.nomor_induk}</small>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className="px-2 py-1 bg-blue-100 text-blue-600 text-xs font-semibold rounded-full">
                                                {a.jadwal_name || '-'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className={`px-2 py-1 text-xs font-semibold rounded-full capitalize ${getStatusBadge(a.status)}`}>
                                                {a.status}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">
                                            {a.minutes_late ? `${a.minutes_late} menit` : '-'}
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">{a.notes || '-'}</td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}
