import React, { useState, useEffect, useRef } from 'react';
import { PageSkeleton } from '../Components/Skeleton';
import LoadingSpinner from '../Components/LoadingSpinner';

export default function AbsensiLangsung() {
    const [loading, setLoading] = useState(true);
    const [jadwalList, setJadwalList] = useState([]);
    const [selectedJadwal, setSelectedJadwal] = useState('');
    const [isAutoRefresh, setIsAutoRefresh] = useState(false);
    const [isRefreshing, setIsRefreshing] = useState(false);
    const [stats, setStats] = useState({ total: 0, hadir: 0, terlambat: 0, belum: 0 });
    const [listHadir, setListHadir] = useState([]);
    const [listTerlambat, setListTerlambat] = useState([]);
    const [listBelum, setListBelum] = useState([]);
    const refreshIntervalRef = useRef(null);

    useEffect(() => {
        document.title = 'Live Attendance - Aktivitas Santri';
        fetchJadwal();

        return () => {
            if (refreshIntervalRef.current) clearInterval(refreshIntervalRef.current);
        };
    }, []);

    const fetchJadwal = async () => {
        try {
            const response = await fetch('/admin/jadwal', {
                credentials: 'include',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (response.ok) {
                const data = await response.json();
                setJadwalList(data.jadwalList || []);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    const loadData = async () => {
        if (!selectedJadwal) return;
        setIsRefreshing(true);

        try {
            const response = await fetch(`/api/live-attendance?jadwal_id=${selectedJadwal}`, {
                credentials: 'include',
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();
            if (data.success) {
                setStats(data.count || { total: 0, hadir: 0, terlambat: 0, belum_hadir: 0 });
                setListHadir(data.hadir || []);
                setListTerlambat(data.terlambat || []);
                setListBelum(data.belum_hadir || []);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setIsRefreshing(false);
        }
    };

    const handleJadwalChange = (e) => {
        const value = e.target.value;
        setSelectedJadwal(value);

        if (refreshIntervalRef.current) clearInterval(refreshIntervalRef.current);

        if (value) {
            setIsAutoRefresh(true);
            setTimeout(loadData, 100);
            refreshIntervalRef.current = setInterval(loadData, 10000);
        } else {
            setIsAutoRefresh(false);
            setStats({ total: 0, hadir: 0, terlambat: 0, belum: 0 });
            setListHadir([]);
            setListTerlambat([]);
            setListBelum([]);
        }
    };

    const handleRefresh = () => {
        loadData();
    };

    const renderList = (items, color) => {
        if (!items || items.length === 0) {
            return <div className="text-center text-gray-400 py-8">{selectedJadwal ? 'Tidak ada data' : 'Pilih jadwal'}</div>;
        }

        return items.map((s, i) => (
            <div key={i} className="flex justify-between items-center px-4 py-3 border-b border-gray-100 last:border-0">
                <div>
                    <div className="font-semibold text-gray-800">{s.nama_lengkap}</div>
                    <small className="text-gray-400">{s.kelas || ''}</small>
                </div>
                {s.waktu_absen && (
                    <span className={`px-2 py-1 text-xs font-semibold rounded-full ${color === 'green' ? 'bg-green-100 text-green-600' :
                        color === 'amber' ? 'bg-amber-100 text-amber-600' :
                            'bg-red-100 text-red-600'
                        }`}>
                        {s.waktu_absen}
                    </span>
                )}
            </div>
        ));
    };

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>
            {/* Header */}
            <div className="flex flex-wrap justify-between items-center gap-4 mb-6">

                <div className="flex gap-3 items-center">
                    <select
                        value={selectedJadwal}
                        onChange={handleJadwalChange}
                        className="px-4 py-2 border border-gray-200 rounded-lg bg-white"
                    >
                        <option value="">-- Pilih Jadwal --</option>
                        {jadwalList.map((j) => (
                            <option key={j.id} value={j.id}>
                                {j.name} ({j.start_time?.substring(0, 5)})
                            </option>
                        ))}
                    </select>
                    <button
                        onClick={handleRefresh}
                        disabled={!selectedJadwal}
                        className="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50"
                    >
                        <i className={`fas fa-sync-alt ${isRefreshing ? 'fa-spin' : ''}`}></i>
                    </button>
                    {isAutoRefresh && (
                        <div className="flex items-center gap-2 px-3 py-1 bg-green-500/10 border border-green-500/20 rounded-full">
                            <LoadingSpinner size="small" />
                            <span className="text-green-600 text-xs font-bold uppercase tracking-wider">Auto Refresh</span>
                        </div>
                    )}
                </div>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div className="bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-2xl p-5 text-center transform hover:-translate-y-1 transition-transform">
                    <div className="text-4xl font-bold">{stats.total || 0}</div>
                    <div>Total Siswa</div>
                </div>
                <div className="bg-gradient-to-br from-green-400 to-green-600 text-white rounded-2xl p-5 text-center transform hover:-translate-y-1 transition-transform">
                    <div className="text-4xl font-bold">{stats.hadir || 0}</div>
                    <div>Hadir</div>
                </div>
                <div className="bg-gradient-to-br from-amber-400 to-amber-600 text-white rounded-2xl p-5 text-center transform hover:-translate-y-1 transition-transform">
                    <div className="text-4xl font-bold">{stats.terlambat || 0}</div>
                    <div>Terlambat</div>
                </div>
                <div className="bg-gradient-to-br from-red-400 to-red-600 text-white rounded-2xl p-5 text-center transform hover:-translate-y-1 transition-transform">
                    <div className="text-4xl font-bold">{stats.belum_hadir || 0}</div>
                    <div>Belum Hadir</div>
                </div>
            </div>

            {/* Lists */}
            <div className="grid lg:grid-cols-3 gap-4">
                {/* Hadir */}
                <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div className="px-4 py-3 border-b bg-green-50">
                        <h6 className="font-bold text-green-600">
                            <i className="fas fa-check-circle mr-2"></i>Hadir
                        </h6>
                    </div>
                    <div className="max-h-96 overflow-y-auto">
                        {renderList(listHadir, 'green')}
                    </div>
                </div>

                {/* Terlambat */}
                <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div className="px-4 py-3 border-b bg-amber-50">
                        <h6 className="font-bold text-amber-600">
                            <i className="fas fa-clock mr-2"></i>Terlambat
                        </h6>
                    </div>
                    <div className="max-h-96 overflow-y-auto">
                        {renderList(listTerlambat, 'amber')}
                    </div>
                </div>

                {/* Belum Hadir */}
                <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div className="px-4 py-3 border-b bg-red-50">
                        <h6 className="font-bold text-red-600">
                            <i className="fas fa-times-circle mr-2"></i>Belum Hadir
                        </h6>
                    </div>
                    <div className="max-h-96 overflow-y-auto">
                        {renderList(listBelum, 'red')}
                    </div>
                </div>
            </div>
        </>
    );
}
