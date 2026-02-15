import React, { useState, useEffect, useRef, useCallback } from 'react';
import axios from 'axios';
import StandaloneLayout from '@/Layouts/StandaloneLayout';
import Swal from 'sweetalert2';

export default function KonfirmasiKembali() {
    // Table data
    const [allData, setAllData] = useState([]);
    const [filteredData, setFilteredData] = useState([]);
    const [stats, setStats] = useState({ total: 0, mendekat: 0, terlambat: 0 });
    const [counts, setCounts] = useState({ semua: 0, keluar: 0, pulang: 0 });
    const [currentCategory, setCurrentCategory] = useState('semua');
    const [filters, setFilters] = useState({ search: '', dateFrom: '', dateTo: '' });
    const [isLoadingData, setIsLoadingData] = useState(true);

    // Scanner & confirmation
    const [isScanning, setIsScanning] = useState(false);
    const [kodeInput, setKodeInput] = useState('');
    const [result, setResult] = useState(null);
    const [detailModal, setDetailModal] = useState(null);
    const [errorModal, setErrorModal] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const scannerRef = useRef(null);
    const readerRef = useRef(null);

    useEffect(() => {
        document.title = 'Konfirmasi Kembali - Aktivitas Santri';
        loadData();
        return () => { stopScanner(); };
    }, []);

    useEffect(() => {
        applyFilters();
    }, [allData, filters, currentCategory]);

    // ========== TABLE DATA FUNCTIONS ==========

    const loadData = async () => {
        setIsLoadingData(true);
        try {
            const response = await axios.get('/api/public/santri-izin-aktif');
            if (response.data.status === 'success') {
                setAllData(response.data.data);
                calculateStats(response.data.data);
            }
        } catch (error) {
            console.error('Error loading data:', error);
        } finally {
            setIsLoadingData(false);
        }
    };

    const calculateStats = (data) => {
        let total = data.length;
        let mendekat = 0;
        let terlambat = 0;
        let keluar = 0;
        let pulang = 0;

        const now = new Date();

        data.forEach(item => {
            if (item.kategori === 'izin_keluar') keluar++;
            if (item.kategori === 'izin_pulang') pulang++;

            if (item.batas_waktu_raw) {
                const batas = new Date(item.batas_waktu_raw);
                const diffMs = batas - now;
                const diffMins = Math.floor(diffMs / 60000);

                if (diffMs < 0) terlambat++;
                else if (diffMins <= 30) mendekat++;
            }
        });

        setStats({ total, mendekat, terlambat });
        setCounts({ semua: total, keluar, pulang });
    };

    const applyFilters = () => {
        let filtered = [...allData];

        if (currentCategory !== 'semua') {
            filtered = filtered.filter(item => item.kategori === currentCategory);
        }

        if (filters.search) {
            const s = filters.search.toLowerCase();
            filtered = filtered.filter(item => item.nama_lengkap.toLowerCase().includes(s));
        }

        if (filters.dateFrom) {
            const dFrom = new Date(filters.dateFrom + 'T00:00:00');
            filtered = filtered.filter(item => {
                if (!item.tanggal_raw && !item.tanggal) return false;
                const itemDate = new Date(item.tanggal_raw || item.tanggal.split(' ')[0].split('/').reverse().join('-'));
                return itemDate >= dFrom;
            });
        }

        if (filters.dateTo) {
            const dTo = new Date(filters.dateTo + 'T23:59:59');
            filtered = filtered.filter(item => {
                if (!item.tanggal_raw && !item.tanggal) return false;
                const itemDate = new Date(item.tanggal_raw || item.tanggal.split(' ')[0].split('/').reverse().join('-'));
                return itemDate <= dTo;
            });
        }

        setFilteredData(filtered);
    };

    const getTimeStatus = (batasWaktuRaw) => {
        if (!batasWaktuRaw) return { label: 'Tidak ada batas', className: 'bg-slate-200 text-slate-700' };

        const now = new Date();
        const batas = new Date(batasWaktuRaw);
        const diffMs = batas - now;
        const diffMins = Math.floor(diffMs / 60000);

        if (diffMs < 0) {
            const telatMins = Math.abs(diffMins);
            const label = telatMins < 60 ? `Lewat ${telatMins}m` : `Lewat ${Math.floor(telatMins / 60)}j`;
            return { label, className: 'bg-red-100 text-red-700' };
        } else if (diffMins <= 30) {
            return { label: `${diffMins}m lagi`, className: 'bg-amber-100 text-amber-700' };
        } else {
            return { label: 'Tepat Waktu', className: 'bg-emerald-100 text-emerald-700' };
        }
    };

    // ========== SCANNER FUNCTIONS (same as Pemindai) ==========

    const startScanner = async () => {
        setIsScanning(true);
        try {
            const { Html5Qrcode } = await import('html5-qrcode');
            scannerRef.current = new Html5Qrcode("qr-reader-konfirmasi");

            await scannerRef.current.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                () => { }
            );
        } catch (err) {
            console.error('Camera error:', err);
            alert('Tidak dapat mengakses kamera: ' + err);
            stopScanner();
        }
    };

    const stopScanner = useCallback(() => {
        if (scannerRef.current) {
            scannerRef.current.stop().catch(() => { });
            scannerRef.current = null;
        }
        setIsScanning(false);
    }, []);

    const onScanSuccess = (decodedText) => {
        if (scannerRef.current) {
            scannerRef.current.pause();
        }
        searchByKode(decodedText.trim());
    };

    const resumeScanner = () => {
        if (scannerRef.current && isScanning) {
            try { scannerRef.current.resume(); } catch (e) { }
        }
    };

    const searchByKode = async (kode) => {
        if (!kode) {
            Swal.fire({
                icon: 'warning',
                title: 'Kode Kosong',
                text: 'Masukkan kode konfirmasi terlebih dahulu'
            });
            return;
        }

        setIsLoading(true);
        try {
            const formData = new FormData();
            formData.append('kode', kode);

            const response = await axios.post('/api/public/konfirmasi/search', formData);
            const result = response.data;

            if (result.status === 'success') {
                setDetailModal(result.data);
            } else {
                setErrorModal(result.message || 'Kode tidak ditemukan');
            }
        } catch (error) {
            console.error('Search error:', error);
            setErrorModal('Terjadi kesalahan: ' + (error.response?.data?.message || error.message));
        } finally {
            setIsLoading(false);
        }
    };

    const handleSearch = () => {
        searchByKode(kodeInput.trim());
    };

    const handleKeyPress = (e) => {
        if (e.key === 'Enter') handleSearch();
    };

    const handleKonfirmasi = async () => {
        if (!detailModal) return;

        setIsLoading(true);
        try {
            const formData = new FormData();
            formData.append('id', detailModal.id);

            const response = await axios.post('/api/public/konfirmasi/direct', formData);
            const result = response.data;

            if (result.status === 'success') {
                setResult({ success: true, name: detailModal.nama_lengkap, message: result.message });
                setTimeout(() => setResult(null), 3000);
                setKodeInput('');
                loadData(); // refresh table
            } else {
                setResult({ success: false, name: detailModal.nama_lengkap, message: result.message });
                setTimeout(() => setResult(null), 3000);
            }
        } catch (error) {
            console.error('Confirmation error:', error);
            setResult({
                success: false,
                name: 'Error',
                message: 'Terjadi kesalahan: ' + (error.response?.data?.message || error.message)
            });
            setTimeout(() => setResult(null), 3000);
        } finally {
            setIsLoading(false);
            setDetailModal(null);
            resumeScanner();
        }
    };

    const getKategoriBadge = (kategori) => {
        if (kategori === 'izin_keluar') return 'bg-amber-100 text-amber-500';
        if (kategori === 'izin_pulang') return 'bg-orange-100 text-orange-500';
        return 'bg-blue-100 text-blue-500';
    };

    return (
        <StandaloneLayout>
            {/* Header */}
            <div className="bg-gradient-to-r from-slate-800 to-slate-900 text-white p-4 md:p-6">
                <div className="max-w-7xl mx-auto">
                    <h1 className="text-xl md:text-2xl font-bold flex items-center">
                        <i className="fas fa-check-circle mr-3 text-emerald-400"></i>
                        Konfirmasi Kembali
                    </h1>
                    <p className="opacity-70 mt-1 text-sm">Scan QR Code atau masukkan kode unik untuk konfirmasi santri sudah kembali</p>
                </div>
            </div>

            <div className="max-w-7xl mx-auto p-4 md:p-6">
                {/* Stats */}
                <div className="grid grid-cols-3 gap-3 mb-6">
                    <div className="bg-white p-3 rounded-xl shadow-sm text-center border border-slate-100">
                        <div className="text-xl font-bold text-blue-600">{stats.total}</div>
                        <div className="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Sedang Izin</div>
                    </div>
                    <div className="bg-white p-3 rounded-xl shadow-sm text-center border border-slate-100">
                        <div className="text-xl font-bold text-amber-600">{stats.mendekat}</div>
                        <div className="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Hampir Batas</div>
                    </div>
                    <div className="bg-white p-3 rounded-xl shadow-sm text-center border border-slate-100">
                        <div className="text-xl font-bold text-red-600">{stats.terlambat}</div>
                        <div className="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Terlambat</div>
                    </div>
                </div>

                {/* 2-Card Layout */}
                <div className="grid md:grid-cols-2 gap-6">
                    {/* Left Card - Scanner & Input (same as Pemindai) */}
                    <div className="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
                        {/* Scanner Area */}
                        {isScanning ? (
                            <div>
                                <div className="bg-slate-50 rounded-xl p-4 text-center mb-3">
                                    <i className="fas fa-camera text-2xl text-emerald-500 mb-2"></i>
                                    <p className="text-sm text-slate-500 mb-0">Arahkan kamera ke QR Code slip izin santri</p>
                                </div>

                                <div id="qr-reader-konfirmasi" ref={readerRef} className="mb-3 rounded-xl overflow-hidden"></div>

                                <button
                                    onClick={stopScanner}
                                    className="w-full py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors"
                                >
                                    <i className="fas fa-stop mr-2"></i>Tutup Kamera
                                </button>
                            </div>
                        ) : (
                            <div>
                                <div className="bg-slate-50 rounded-xl p-6 text-center mb-4">
                                    <i className="fas fa-qrcode text-5xl text-emerald-500 mb-3"></i>
                                    <h6 className="font-bold mb-1 text-slate-800">Scan QR Code</h6>
                                    <p className="text-sm text-slate-500 mb-0">Scan QR Code slip izin atau masukkan kode unik</p>
                                </div>

                                <button
                                    onClick={startScanner}
                                    className="w-full py-3 bg-emerald-500 text-white rounded-xl font-bold text-lg hover:bg-emerald-600 transition-colors"
                                >
                                    <i className="fas fa-camera mr-2"></i>Mulai Scan
                                </button>
                            </div>
                        )}

                        {/* Divider */}
                        <div className="flex items-center my-6">
                            <div className="flex-1 border-b border-slate-200"></div>
                            <span className="px-4 text-sm text-slate-400">atau masukkan kode</span>
                            <div className="flex-1 border-b border-slate-200"></div>
                        </div>

                        {/* Manual Input */}
                        <div className="mb-3">
                            <input
                                type="text"
                                value={kodeInput}
                                onChange={(e) => setKodeInput(e.target.value.toUpperCase())}
                                onKeyPress={handleKeyPress}
                                placeholder="Masukkan Kode Unik"
                                maxLength={10}
                                className="w-full text-center text-3xl font-black tracking-[0.3em] uppercase py-5 px-4 border-3 border-emerald-400 rounded-xl bg-emerald-50 text-gray-800 placeholder:text-emerald-300 placeholder:tracking-widest placeholder:text-lg placeholder:font-semibold focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all"
                            />
                        </div>
                        <button
                            onClick={handleSearch}
                            disabled={isLoading}
                            className="w-full py-3 border-2 border-emerald-500 text-emerald-500 rounded-xl font-semibold hover:bg-emerald-50 transition-colors disabled:opacity-50"
                        >
                            {isLoading ? (
                                <><i className="fas fa-spinner fa-spin mr-2"></i>Mencari...</>
                            ) : (
                                <><i className="fas fa-search mr-2"></i>Cari</>
                            )}
                        </button>

                        {/* Result Area */}
                        {result && (
                            <div className={`mt-4 rounded-xl p-6 text-center text-white ${result.success ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : 'bg-gradient-to-br from-red-500 to-red-600'}`}>
                                <i className={`fas fa-${result.success ? 'check-circle' : 'times-circle'} text-5xl mb-2`}></i>
                                <h5 className="font-bold mb-1">{result.name}</h5>
                                <p className="mb-0">{result.message}</p>
                            </div>
                        )}
                    </div>

                    {/* Right Card - Table Daftar Santri Izin Aktif */}
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        {/* Table Header */}
                        <div className="p-4 bg-emerald-600 text-white flex justify-between items-center font-bold">
                            <span className="text-sm"><i className="fas fa-list mr-2"></i>Daftar Santri Izin Aktif</span>
                            <button onClick={loadData} className="hover:rotate-180 transition-transform duration-500">
                                <i className="fas fa-sync-alt"></i>
                            </button>
                        </div>

                        {/* Category Tabs */}
                        <div className="flex border-b border-slate-100">
                            {[
                                { id: 'semua', label: 'Semua', count: counts.semua },
                                { id: 'izin_keluar', label: 'Keluar', count: counts.keluar },
                                { id: 'izin_pulang', label: 'Pulang', count: counts.pulang }
                            ].map(cat => (
                                <button
                                    key={cat.id}
                                    onClick={() => setCurrentCategory(cat.id)}
                                    className={`flex-1 py-2 text-xs font-bold transition-colors ${currentCategory === cat.id
                                        ? 'text-emerald-600 border-b-2 border-emerald-500 bg-emerald-50'
                                        : 'text-slate-400 hover:text-slate-600'
                                        }`}
                                >
                                    {cat.label} <span className="ml-1 text-[10px]">({cat.count})</span>
                                </button>
                            ))}
                        </div>

                        {/* Search & Filters */}
                        <div className="p-3 border-b border-slate-100 space-y-2">
                            <div className="relative">
                                <i className="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input
                                    type="text"
                                    placeholder="Cari nama..."
                                    value={filters.search}
                                    onChange={(e) => setFilters({ ...filters, search: e.target.value })}
                                    className="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500"
                                />
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="date"
                                    value={filters.dateFrom}
                                    onChange={(e) => setFilters({ ...filters, dateFrom: e.target.value })}
                                    className="flex-1 min-w-0 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-emerald-500"
                                />
                                <span className="text-slate-400 text-xs font-semibold shrink-0">s/d</span>
                                <input
                                    type="date"
                                    value={filters.dateTo}
                                    onChange={(e) => setFilters({ ...filters, dateTo: e.target.value })}
                                    className="flex-1 min-w-0 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-emerald-500"
                                />
                                <button
                                    onClick={() => setFilters({ search: '', dateFrom: '', dateTo: '' })}
                                    className="p-2 bg-slate-100 text-slate-500 rounded-lg hover:bg-slate-200 shrink-0"
                                    title="Reset Filter"
                                >
                                    <i className="fas fa-undo text-xs"></i>
                                </button>
                            </div>
                        </div>

                        {/* Table */}
                        <div className="overflow-x-auto max-h-[500px] overflow-y-auto">
                            <table className="w-full text-left">
                                <thead className="bg-slate-50 border-b border-slate-100 sticky top-0">
                                    <tr>
                                        <th className="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Santri</th>
                                        <th className="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Jenis</th>
                                        <th className="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Batas</th>
                                        <th className="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {isLoadingData ? (
                                        <tr>
                                            <td colSpan="4" className="p-8 text-center">
                                                <div className="animate-spin inline-block w-6 h-6 border-3 border-emerald-500 border-t-transparent rounded-full mb-2"></div>
                                                <p className="text-slate-500 text-xs">Memuat data...</p>
                                            </td>
                                        </tr>
                                    ) : filteredData.length > 0 ? (
                                        filteredData.map(item => {
                                            const status = getTimeStatus(item.batas_waktu_raw);
                                            return (
                                                <tr key={item.id} className="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                                    <td className="px-3 py-2">
                                                        <div className="flex items-center gap-2">
                                                            <div className="w-7 h-7 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                                                {item.nama_lengkap.substring(0, 1).toUpperCase()}
                                                            </div>
                                                            <div>
                                                                <div className="font-bold text-xs text-slate-800">{item.nama_lengkap}</div>
                                                                <div className="text-[10px] text-slate-500">Kelas {item.kelas}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td className="px-3 py-2">
                                                        <span className={`px-2 py-0.5 rounded-md text-[10px] font-bold uppercase ${getKategoriBadge(item.kategori)}`}>
                                                            {item.kategori_label}
                                                        </span>
                                                    </td>
                                                    <td className="px-3 py-2 text-[11px] font-mono font-bold text-slate-600">
                                                        {item.batas_waktu || '-'}
                                                    </td>
                                                    <td className="px-3 py-2">
                                                        <span className={`px-2 py-0.5 rounded-md text-[10px] font-bold ${status.className}`}>
                                                            {status.label}
                                                        </span>
                                                    </td>
                                                </tr>
                                            );
                                        })
                                    ) : (
                                        <tr>
                                            <td colSpan="4" className="p-12 text-center opacity-40">
                                                <i className="fas fa-inbox text-4xl mb-3 text-slate-300 block"></i>
                                                <p className="text-sm text-slate-500">Belum ada data izin aktif</p>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {/* Detail Modal (confirmation) */}
            {detailModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="absolute inset-0 bg-black/50" onClick={() => { setDetailModal(null); resumeScanner(); }}></div>
                    <div className="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
                        {/* Header */}
                        <div className="bg-gradient-to-r from-emerald-500 to-emerald-400 text-white px-6 py-4 flex items-center justify-between">
                            <h5 className="font-bold flex items-center gap-2">
                                <i className="fas fa-clipboard-check"></i>Detail Izin
                            </h5>
                            <button onClick={() => { setDetailModal(null); resumeScanner(); }} className="text-white/80 hover:text-white">
                                <i className="fas fa-times"></i>
                            </button>
                        </div>

                        {/* Body */}
                        <div className="p-6">
                            {/* Avatar & Name */}
                            <div className="text-center mb-6">
                                <div className="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-400 rounded-full flex items-center justify-center mx-auto text-white text-2xl font-bold">
                                    {(detailModal.nama_lengkap || 'S').charAt(0).toUpperCase()}
                                </div>
                                <h5 className="font-bold mt-2 mb-1 text-gray-800">{detailModal.nama_lengkap}</h5>
                                <p className="text-gray-500 text-sm">Kelas {detailModal.kelas}</p>
                            </div>

                            {/* Details */}
                            <div className="space-y-3">
                                <div className="flex justify-between py-2 border-b border-gray-100">
                                    <span className="text-gray-500 text-sm">Jenis Izin</span>
                                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${getKategoriBadge(detailModal.kategori)}`}>
                                        {detailModal.kategori_label}
                                    </span>
                                </div>
                                <div className="flex justify-between py-2 border-b border-gray-100">
                                    <span className="text-gray-500 text-sm">Keperluan</span>
                                    <span className="font-semibold text-gray-800 text-right">{detailModal.judul || '-'}</span>
                                </div>
                                <div className="flex justify-between py-2 border-b border-gray-100">
                                    <span className="text-gray-500 text-sm">Batas Waktu</span>
                                    <span className="font-semibold text-gray-800">{detailModal.batas_waktu || '-'}</span>
                                </div>
                            </div>
                        </div>

                        {/* Footer */}
                        <div className="px-6 pb-6 flex gap-3">
                            <button
                                onClick={() => { setDetailModal(null); resumeScanner(); }}
                                className="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                onClick={handleKonfirmasi}
                                disabled={isLoading}
                                className="flex-1 py-3 bg-emerald-500 text-white rounded-xl font-semibold hover:bg-emerald-600 transition-colors disabled:opacity-50"
                            >
                                {isLoading ? (
                                    <><i className="fas fa-spinner fa-spin mr-1"></i>Memproses...</>
                                ) : (
                                    <><i className="fas fa-check mr-1"></i>Konfirmasi Kembali</>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Error Modal */}
            {errorModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="absolute inset-0 bg-black/50" onClick={() => { setErrorModal(null); resumeScanner(); }}></div>
                    <div className="relative bg-white rounded-2xl shadow-xl w-full max-w-xs overflow-hidden p-6 text-center">
                        <div className="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i className="fas fa-times text-white text-2xl"></i>
                        </div>
                        <h5 className="font-bold mb-2 text-gray-800">Tidak Ditemukan</h5>
                        <p className="text-gray-500 mb-4">{errorModal}</p>
                        <button
                            onClick={() => { setErrorModal(null); resumeScanner(); }}
                            className="w-full py-3 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-colors"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            )}
        </StandaloneLayout>
    );
}
