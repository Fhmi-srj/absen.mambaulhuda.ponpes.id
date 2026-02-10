import React, { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import { PageSkeleton } from '../Components/Skeleton';
import axios from 'axios';

export default function Riwayat() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState({
        groupedAttendances: {},
        stats: { total: 0, hadir: 0, terlambat: 0, pulang: 0 },
    });
    const [selectedMonth, setSelectedMonth] = useState(searchParams.get('month') || new Date().getMonth() + 1);
    const [selectedYear, setSelectedYear] = useState(searchParams.get('year') || new Date().getFullYear());

    const months = {
        '1': 'Januari', '2': 'Februari', '3': 'Maret', '4': 'April',
        '5': 'Mei', '6': 'Juni', '7': 'Juli', '8': 'Agustus',
        '9': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
    };

    const dayNames = {
        'Sunday': 'Minggu', 'Monday': 'Senin', 'Tuesday': 'Selasa',
        'Wednesday': 'Rabu', 'Thursday': 'Kamis', 'Friday': 'Jumat', 'Saturday': 'Sabtu'
    };

    useEffect(() => {
        document.title = 'Riwayat Kehadiran - Aktivitas Santri';
        fetchData();
    }, []);

    const fetchData = async (month = selectedMonth, year = selectedYear) => {
        setLoading(true);
        try {
            const response = await axios.get(`/api/riwayat?month=${month}&year=${year}`);
            setData(response.data);
        } catch (error) {
            console.error('Error fetching riwayat:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleFilter = () => {
        setSearchParams({ month: selectedMonth, year: selectedYear });
        fetchData(selectedMonth, selectedYear);
    };

    const formatTime = (time) => {
        if (!time) return '-';
        return time.substring(0, 5);
    };

    const getDayName = (dateStr) => {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        const dayEnglish = d.toLocaleDateString('en-US', { weekday: 'long' });
        return dayNames[dayEnglish] || dayEnglish;
    };

    const formatDateIndo = (dateStr) => {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    };

    const getStatusBadge = (status) => {
        const styles = {
            hadir: 'bg-green-100 text-green-700',
            terlambat: 'bg-amber-100 text-amber-700',
            pulang: 'bg-blue-100 text-blue-700',
        };
        return styles[status] || 'bg-gray-100 text-gray-600';
    };

    if (loading) {
        return <PageSkeleton />;
    }

    const years = [];
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= currentYear - 5; y--) {
        years.push(y);
    }

    const sortedDates = Object.keys(data.groupedAttendances || {}).sort((a, b) => new Date(b) - new Date(a));

    return (
        <>


            {/* Stats Cards */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div className="bg-white rounded-xl p-4 shadow-sm text-center">
                    <div className="text-2xl font-bold text-gray-800">{data.stats?.total || 0}</div>
                    <div className="text-sm text-gray-500">Total Absensi</div>
                </div>
                <div className="bg-white rounded-xl p-4 shadow-sm text-center">
                    <div className="text-2xl font-bold text-green-600">{data.stats?.hadir || 0}</div>
                    <div className="text-sm text-gray-500">Hadir</div>
                </div>
                <div className="bg-white rounded-xl p-4 shadow-sm text-center">
                    <div className="text-2xl font-bold text-amber-600">{data.stats?.terlambat || 0}</div>
                    <div className="text-sm text-gray-500">Terlambat</div>
                </div>
                <div className="bg-white rounded-xl p-4 shadow-sm text-center">
                    <div className="text-2xl font-bold text-blue-600">{data.stats?.pulang || 0}</div>
                    <div className="text-sm text-gray-500">Pulang</div>
                </div>
            </div>

            {/* Filter */}
            <div className="bg-white rounded-xl shadow-sm p-4 mb-6">
                <div className="flex flex-wrap items-center gap-3">
                    <select
                        value={selectedMonth}
                        onChange={(e) => setSelectedMonth(e.target.value)}
                        className="px-3 py-2 border border-gray-200 rounded-lg"
                    >
                        {Object.entries(months).map(([val, label]) => (
                            <option key={val} value={val}>{label}</option>
                        ))}
                    </select>
                    <select
                        value={selectedYear}
                        onChange={(e) => setSelectedYear(e.target.value)}
                        className="px-3 py-2 border border-gray-200 rounded-lg"
                    >
                        {years.map(y => (
                            <option key={y} value={y}>{y}</option>
                        ))}
                    </select>
                    <button
                        onClick={handleFilter}
                        className="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600"
                    >
                        <i className="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </div>

            {/* Attendance List */}
            {sortedDates.length === 0 ? (
                <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i className="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p className="text-gray-500">Tidak ada data kehadiran untuk bulan ini</p>
                </div>
            ) : (
                <div className="space-y-4">
                    {sortedDates.map(date => (
                        <div key={date} className="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div className="px-4 py-3 bg-gray-50 border-b border-gray-100/50 flex flex-wrap items-center justify-between gap-2">
                                <div className="font-semibold text-gray-800 text-sm sm:text-base">
                                    {getDayName(date)}, {formatDateIndo(date)}
                                </div>
                                <span className="text-xs sm:text-sm text-gray-500">
                                    {data.groupedAttendances[date]?.length || 0} data
                                </span>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="w-full min-w-[650px]">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Waktu</th>
                                            <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Nama</th>
                                            <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Kelas</th>
                                            <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Jadwal</th>
                                            <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-100/50">
                                        {data.groupedAttendances[date]?.map((att, idx) => (
                                            <tr key={idx} className="hover:bg-gray-50">
                                                <td className="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">{formatTime(att.attendance_time)}</td>
                                                <td className="px-5 py-4 whitespace-nowrap">
                                                    <div className="font-medium text-gray-800">{att.nama_lengkap || '-'}</div>
                                                </td>
                                                <td className="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">{att.kelas || '-'}</td>
                                                <td className="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">{att.jadwal_name || '-'}</td>
                                                <td className="px-5 py-4 whitespace-nowrap">
                                                    <span className={`px-2 py-1 text-xs font-medium rounded capitalize ${getStatusBadge(att.status)}`}>
                                                        {att.status || '-'}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </>
    );
}
