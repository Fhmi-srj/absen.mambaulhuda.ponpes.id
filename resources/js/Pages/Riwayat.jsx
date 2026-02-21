import React, { useState, useEffect, useRef, useCallback } from 'react';
import { PageSkeleton } from '../Components/Skeleton';
import LoadingSpinner from '../Components/LoadingSpinner';

export default function Riwayat() {
    const [pageLoading, setPageLoading] = useState(true);
    const [dataLoading, setDataLoading] = useState(false);
    const [attendances, setAttendances] = useState([]);
    const [jadwalList, setJadwalList] = useState([]);
    const [classList, setClassList] = useState([]);
    const [filterDateFrom, setFilterDateFrom] = useState('');
    const [filterDateTo, setFilterDateTo] = useState('');
    const [filterJadwal, setFilterJadwal] = useState('');
    const [filterStatus, setFilterStatus] = useState('');
    const [filterKelas, setFilterKelas] = useState('');
    const [stats, setStats] = useState({ total: 0, hadir: 0, terlambat: 0, alpha: 0 });
    const [currentPage, setCurrentPage] = useState(1);
    const perPage = 10;
    const [showPrintModal, setShowPrintModal] = useState(false);
    const [iframeLoading, setIframeLoading] = useState(true);
    const printIframeRef = useRef(null);
    const isFirstLoad = useRef(true);

    const fetchData = useCallback(async () => {
        try {
            const params = new URLSearchParams({
                date_from: filterDateFrom,
                date_to: filterDateTo,
                jadwal: filterJadwal,
                status: filterStatus,
                kelas: filterKelas
            });
            const response = await fetch(`/api/riwayat?${params}`, {
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
                setClassList(data.classList || []);
                setStats({
                    total: data.attendances?.length || 0,
                    hadir: data.totalHadir || 0,
                    terlambat: data.totalTerlambat || 0,
                    alpha: data.totalAlpha || 0,
                });
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            setPageLoading(false);
            setDataLoading(false);
        }
    }, [filterDateFrom, filterDateTo, filterJadwal, filterStatus, filterKelas]);

    // Initial load
    useEffect(() => {
        document.title = 'Riwayat Kehadiran - Aktivitas Santri';
        fetchData();
    }, []);

    // Auto-filter on change (skip initial)
    useEffect(() => {
        if (isFirstLoad.current) {
            isFirstLoad.current = false;
            return;
        }
        setDataLoading(true);
        setCurrentPage(1);
        const timer = setTimeout(() => {
            fetchData();
        }, 300);
        return () => clearTimeout(timer);
    }, [filterDateFrom, filterDateTo, filterJadwal, filterStatus, filterKelas]);

    const getStatusBadge = (status) => {
        const badges = {
            hadir: 'bg-green-100 text-green-600',
            terlambat: 'bg-amber-100 text-amber-600',
            alpha: 'bg-red-100 text-red-600',
            izin: 'bg-blue-100 text-blue-600',
        };
        return badges[status] || 'bg-gray-100 text-gray-600';
    };

    const formatDate = (dateStr) => {
        if (!dateStr) return '-';
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    };

    const formatTime = (timeStr) => {
        if (!timeStr) return '-';
        return timeStr.substring(0, 5);
    };

    const buildExportParams = () => {
        return new URLSearchParams({
            date_from: filterDateFrom,
            date_to: filterDateTo,
            jadwal: filterJadwal,
            status: filterStatus,
            kelas: filterKelas
        }).toString();
    };

    const handlePrintPreview = () => {
        setIframeLoading(true);
        setShowPrintModal(true);
    };

    const handlePrint = () => {
        if (printIframeRef.current) {
            printIframeRef.current.contentWindow.print();
        }
    };


    if (pageLoading) {
        return <PageSkeleton />;
    }

    return (
        <>
            {/* Filters */}
            <div className="bg-white rounded-xl shadow-sm p-4 mb-4">
                {/* Date Range Row */}
                <div className="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label className="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                        <input type="date" value={filterDateFrom} onChange={(e) => setFilterDateFrom(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
                    </div>
                    <div>
                        <label className="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                        <input type="date" value={filterDateTo} onChange={(e) => setFilterDateTo(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
                    </div>
                </div>
                {/* Dropdowns Row */}
                <div className="grid grid-cols-3 gap-3 mb-3">
                    <div>
                        <label className="block text-xs text-gray-500 mb-1">Jadwal</label>
                        <select value={filterJadwal} onChange={(e) => setFilterJadwal(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua Jadwal</option>
                            {jadwalList.map((j) => (
                                <option key={j.id} value={j.id}>{j.name}</option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="block text-xs text-gray-500 mb-1">Status</label>
                        <select value={filterStatus} onChange={(e) => setFilterStatus(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div>
                        <label className="block text-xs text-gray-500 mb-1">Kelas</label>
                        <select value={filterKelas} onChange={(e) => setFilterKelas(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua Kelas</option>
                            {classList.map((k) => (
                                <option key={k} value={k}>{k}</option>
                            ))}
                        </select>
                    </div>
                </div>
                {/* Action Buttons */}
                <div className="flex gap-2">
                    <a href={`/admin/riwayat/export?${buildExportParams()}`}
                        className="flex-1 md:flex-none px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold inline-flex items-center justify-center hover:bg-emerald-600 transition-colors">
                        <i className="fas fa-file-excel mr-1"></i>Excel
                    </a>
                    <button type="button" onClick={handlePrintPreview}
                        className="flex-1 md:flex-none px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold inline-flex items-center justify-center hover:bg-red-600 transition-colors">
                        <i className="fas fa-print mr-1"></i>Cetak
                    </button>
                </div>
            </div>

            {/* Stats + Table */}
            {dataLoading ? (
                <LoadingSpinner size="medium" text="Memuat data riwayat..." />
            ) : (
                <>
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
                            <div className="text-2xl font-bold text-red-600">{stats.alpha}</div>
                            <div className="text-sm text-gray-500">Alpha</div>
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
                                        attendances.slice((currentPage - 1) * perPage, currentPage * perPage).map((a, i) => (
                                            <tr key={i} className="hover:bg-gray-50">
                                                <td className="px-4 py-3">
                                                    <div className="text-xs text-gray-400">{formatDate(a.attendance_date)}</div>
                                                    <div className="font-semibold text-gray-800">{formatTime(a.attendance_time)}</div>
                                                </td>
                                                <td className="px-4 py-3">
                                                    <div className="font-semibold text-gray-800">{a.nama_lengkap}</div>
                                                    <small className="text-gray-400">{a.kelas} - {a.nomor_induk}</small>
                                                </td>
                                                <td className="px-4 py-3">
                                                    <span className="px-2 py-1 bg-blue-100 text-blue-600 text-xs font-semibold rounded-full whitespace-nowrap inline-block max-w-[150px] truncate" title={a.jadwal_name || '-'}>
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

                        {/* Pagination */}
                        {Math.ceil(attendances.length / perPage) > 1 && (
                            <div className="px-4 py-3 border-t border-gray-100/50 flex items-center justify-between">
                                <span className="text-sm text-gray-500">
                                    Menampilkan {(currentPage - 1) * perPage + 1} - {Math.min(currentPage * perPage, attendances.length)} dari {attendances.length}
                                </span>
                                <div className="flex gap-1">
                                    <button
                                        onClick={() => setCurrentPage(Math.max(1, currentPage - 1))}
                                        disabled={currentPage === 1}
                                        className="px-3 py-1 border border-gray-200 rounded-lg disabled:opacity-50"
                                    >
                                        <i className="fas fa-chevron-left"></i>
                                    </button>
                                    <button
                                        onClick={() => setCurrentPage(Math.min(Math.ceil(attendances.length / perPage), currentPage + 1))}
                                        disabled={currentPage === Math.ceil(attendances.length / perPage)}
                                        className="px-3 py-1 border border-gray-200 rounded-lg disabled:opacity-50"
                                    >
                                        <i className="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </>
            )}

            {/* Print Preview Modal */}
            {showPrintModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="fixed inset-0 bg-black/50" onClick={() => setShowPrintModal(false)}></div>
                    <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
                        <div className="px-6 py-4 bg-red-500 text-white flex items-center justify-between flex-shrink-0">
                            <h6 className="font-bold"><i className="fas fa-print mr-2"></i>PREVIEW CETAK</h6>
                            <button onClick={() => setShowPrintModal(false)} className="text-white/80 hover:text-white">
                                <i className="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div className="flex-1 overflow-hidden bg-gray-100 p-4 relative">
                            {iframeLoading && (
                                <div className="absolute inset-0 flex items-center justify-center z-10 bg-gray-100">
                                    <LoadingSpinner size="medium" text="Memuat preview cetak..." />
                                </div>
                            )}
                            <iframe
                                ref={printIframeRef}
                                src={`/admin/riwayat/export-pdf?${buildExportParams()}&embed=1`}
                                className="w-full h-full bg-white rounded-lg shadow-inner border border-gray-200"
                                style={{ minHeight: '400px' }}
                                onLoad={() => setIframeLoading(false)}
                            />
                        </div>
                        <div className="px-6 py-4 bg-gray-50 flex justify-end gap-3 flex-shrink-0 border-t border-gray-100">
                            <button onClick={() => setShowPrintModal(false)} className="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg font-medium text-sm">
                                Tutup
                            </button>
                            <button onClick={handlePrint} className="px-4 py-2 bg-red-500 text-white rounded-lg font-bold shadow text-sm">
                                <i className="fas fa-print mr-1"></i> CETAK
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}
