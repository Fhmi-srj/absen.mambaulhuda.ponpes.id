import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import StandaloneLayout from '@/Layouts/StandaloneLayout';
import { Html5Qrcode } from "html5-qrcode";

export default function KonfirmasiKembali() {
    const [allData, setAllData] = useState([]);
    const [filteredData, setFilteredData] = useState([]);
    const [stats, setStats] = useState({ total: 0, mendekat: 0, terlambat: 0 });
    const [counts, setCounts] = useState({ semua: 0, keluar: 0, pulang: 0 });
    const [currentCategory, setCurrentCategory] = useState('semua');
    const [filters, setFilters] = useState({ search: '', dateFrom: '', dateTo: '' });
    const [isLoading, setIsLoading] = useState(true);

    const [selectedItem, setSelectedItem] = useState(null);
    const [inputKode, setInputKode] = useState('');
    const [isScannerActive, setIsScannerActive] = useState(false);
    const [showModal, setShowModal] = useState(false);

    const scannerRef = useRef(null);

    useEffect(() => {
        document.title = 'Konfirmasi Kembali - Aktivitas Santri';
        loadData();
    }, []);

    useEffect(() => {
        applyFilters();
    }, [allData, filters, currentCategory]);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const response = await axios.get('/api/public/santri-izin-aktif');
            if (response.data.status === 'success') {
                setAllData(response.data.data);
                calculateStats(response.data.data);
            }
        } catch (error) {
            console.error('Error loading data:', error);
        } finally {
            setIsLoading(false);
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
            const dFrom = new Date(filters.dateFrom);
            filtered = filtered.filter(item => {
                const itemDate = new Date(item.tanggal_raw || item.tanggal.split('/').reverse().join('-'));
                return itemDate >= dFrom;
            });
        }

        if (filters.dateTo) {
            const dTo = new Date(filters.dateTo);
            filtered = filtered.filter(item => {
                const itemDate = new Date(item.tanggal_raw || item.tanggal.split('/').reverse().join('-'));
                return itemDate <= dTo;
            });
        }

        setFilteredData(filtered);
    };

    const handleKonfirmasiKlik = (item) => {
        setSelectedItem(item);
        setInputKode('');
        setShowModal(true);
    };

    const submitKonfirmasi = async () => {
        if (!inputKode) return alert('Masukkan kode konfirmasi');
        try {
            const response = await axios.post('/api/public/konfirmasi-kembali', {
                id: selectedItem.id,
                kode: inputKode
            });
            if (response.data.status === 'success') {
                alert('Berhasil: ' + response.data.message);
                setShowModal(false);
                loadData();
            }
        } catch (error) {
            alert('Gagal: ' + (error.response?.data?.message || 'Terjadi kesalahan'));
        }
    };

    const startScanner = async () => {
        setIsScannerActive(true);
        setTimeout(() => {
            const html5QrCode = new Html5Qrcode("qr-reader");
            scannerRef.current = html5QrCode;
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    setInputKode(decodedText);
                    stopScanner();
                },
                (errorMessage) => { }
            ).catch(err => {
                console.error("Scanner error:", err);
                setIsScannerActive(false);
            });
        }, 100);
    };

    const stopScanner = () => {
        if (scannerRef.current) {
            scannerRef.current.stop().then(() => {
                setIsScannerActive(false);
            }).catch(err => console.error(err));
        } else {
            setIsScannerActive(false);
        }
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

    return (
        <StandaloneLayout>

            <div className="bg-gradient-to-r from-slate-800 to-slate-900 text-white p-6 md:p-10">
                <div className="max-w-6xl mx-auto">
                    <h1 className="text-3xl font-bold flex items-center">
                        <i className="fas fa-check-circle mr-4 text-emerald-400"></i>
                        Konfirmasi Kembali
                    </h1>
                    <p className="opacity-70 mt-2">Pilih nama santri untuk konfirmasi sudah kembali ke pondok</p>
                </div>
            </div>

            <div className="max-w-6xl mx-auto p-4 md:p-8">
                {/* Stats */}
                <div className="grid grid-cols-3 gap-4 mb-8">
                    <div className="bg-white p-4 rounded-2xl shadow-sm text-center border border-slate-100">
                        <div className="text-2xl font-bold text-blue-600">{stats.total}</div>
                        <div className="text-xs text-slate-500 uppercase tracking-wider font-semibold">Sedang Izin</div>
                    </div>
                    <div className="bg-white p-4 rounded-2xl shadow-sm text-center border border-slate-100">
                        <div className="text-2xl font-bold text-amber-600">{stats.mendekat}</div>
                        <div className="text-xs text-slate-500 uppercase tracking-wider font-semibold">Hampir Batas</div>
                    </div>
                    <div className="bg-white p-4 rounded-2xl shadow-sm text-center border border-slate-100">
                        <div className="text-2xl font-bold text-red-600">{stats.terlambat}</div>
                        <div className="text-xs text-slate-500 uppercase tracking-wider font-semibold">Terlambat</div>
                    </div>
                </div>

                {/* Categories */}
                <div className="flex gap-3 mb-6 overflow-x-auto pb-2">
                    {[
                        { id: 'semua', label: 'Semua', icon: 'list', count: counts.semua, color: 'blue' },
                        { id: 'izin_keluar', label: 'Izin Keluar', icon: 'sign-out-alt', count: counts.keluar, color: 'amber' },
                        { id: 'izin_pulang', label: 'Izin Pulang', icon: 'home', count: counts.pulang, color: 'orange' }
                    ].map(cat => (
                        <button
                            key={cat.id}
                            onClick={() => setCurrentCategory(cat.id)}
                            className={`flex items-center gap-3 p-4 rounded-xl border-2 transition-all min-w-[140px] flex-1 ${currentCategory === cat.id
                                ? `border-blue-500 bg-blue-50`
                                : 'border-transparent bg-white shadow-sm'
                                }`}
                        >
                            <div className={`w-10 h-10 rounded-lg flex items-center justify-center text-lg ${cat.id === 'semua' ? 'bg-blue-100 text-blue-600' :
                                cat.id === 'izin_keluar' ? 'bg-amber-100 text-amber-600' : 'bg-orange-100 text-orange-600'
                                }`}>
                                <i className={`fas fa-${cat.icon}`}></i>
                            </div>
                            <div className="text-left flex-1">
                                <div className="text-sm font-bold text-slate-800">{cat.label}</div>
                                <span className={`text-[10px] px-2 py-0.5 rounded-full font-bold ${currentCategory === cat.id ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600'
                                    }`}>
                                    {cat.count}
                                </span>
                            </div>
                        </button>
                    ))}
                </div>

                {/* Filters */}
                <div className="bg-white p-4 rounded-2xl shadow-sm mb-6 border border-slate-100 flex flex-wrap gap-3 items-center">
                    <div className="relative flex-1 min-w-[200px]">
                        <i className="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input
                            type="text"
                            placeholder="Cari nama santri..."
                            value={filters.search}
                            onChange={(e) => setFilters({ ...filters, search: e.target.value })}
                            className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500"
                        />
                    </div>
                    <input
                        type="date"
                        value={filters.dateFrom}
                        onChange={(e) => setFilters({ ...filters, dateFrom: e.target.value })}
                        className="p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500"
                    />
                    <input
                        type="date"
                        value={filters.dateTo}
                        onChange={(e) => setFilters({ ...filters, dateTo: e.target.value })}
                        className="p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500"
                    />
                    <button
                        onClick={() => setFilters({ search: '', dateFrom: '', dateTo: '' })}
                        className="p-2 text-slate-500 hover:text-red-500 transition-colors"
                    >
                        <i className="fas fa-times mr-1"></i> Reset
                    </button>
                </div>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div className="p-4 bg-emerald-600 text-white flex justify-between items-center font-bold">
                        <span><i className="fas fa-list mr-2"></i>Daftar Santri Izin Aktif</span>
                        <button onClick={loadData} className="hover:rotate-180 transition-transform duration-500">
                            <i className="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th className="p-4 text-xs font-bold text-slate-500 uppercase">Santri</th>
                                    <th className="p-4 text-xs font-bold text-slate-500 uppercase">Jenis</th>
                                    <th className="p-4 text-xs font-bold text-slate-500 uppercase hidden md:table-cell">Keperluan</th>
                                    <th className="p-4 text-xs font-bold text-slate-500 uppercase">Batas</th>
                                    <th className="p-4 text-xs font-bold text-slate-500 uppercase">Status</th>
                                    <th className="p-4 text-xs font-bold text-slate-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {isLoading ? (
                                    <tr>
                                        <td colSpan="6" className="p-10 text-center">
                                            <div className="animate-spin inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mb-4"></div>
                                            <p className="text-slate-500">Memuat data...</p>
                                        </td>
                                    </tr>
                                ) : filteredData.length > 0 ? (
                                    filteredData.map(item => {
                                        const status = getTimeStatus(item.batas_waktu_raw);
                                        return (
                                            <tr key={item.id} className="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                                <td className="p-4">
                                                    <div className="flex items-center gap-3">
                                                        <div className="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                                            {item.nama_lengkap.substring(0, 1).toUpperCase()}
                                                        </div>
                                                        <div>
                                                            <div className="font-bold text-slate-800">{item.nama_lengkap}</div>
                                                            <div className="text-xs text-slate-500">Kelas {item.kelas}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="p-4">
                                                    <span className={`px-2 py-1 rounded-md text-[10px] font-bold uppercase ${item.kategori === 'izin_keluar' ? 'bg-amber-100 text-amber-500' :
                                                        item.kategori === 'izin_pulang' ? 'bg-orange-100 text-orange-500' :
                                                            'bg-blue-100 text-blue-500'
                                                        }`}>
                                                        {item.kategori_label}
                                                    </span>
                                                </td>
                                                <td className="p-4 hidden md:table-cell text-xs text-slate-600 truncate max-w-[150px]">
                                                    {item.judul}
                                                </td>
                                                <td className="p-4 text-xs font-mono font-bold text-slate-600">
                                                    {item.batas_waktu || '-'}
                                                </td>
                                                <td className="p-4">
                                                    <span className={`px-2 py-1 rounded-md text-[10px] font-bold ${status.className}`}>
                                                        {status.label}
                                                    </span>
                                                </td>
                                                <td className="p-4">
                                                    <button
                                                        onClick={() => handleKonfirmasiKlik(item)}
                                                        className="w-10 h-10 rounded-lg bg-emerald-500 text-white flex items-center justify-center hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20"
                                                    >
                                                        <i className="fas fa-check"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="p-20 text-center opacity-40">
                                            <i className="fas fa-inbox text-6xl mb-4 text-slate-300"></i>
                                            <p className="text-lg text-slate-500">Belum ada data izin aktif</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Confirmation Modal */}
            {showModal && selectedItem && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-300">
                    <div className="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                        <div className="p-4 bg-emerald-600 text-white font-bold flex justify-between items-center">
                            <span><i className="fas fa-user-check mr-2"></i>Konfirmasi Kembali</span>
                            <button onClick={() => setShowModal(false)} className="text-white/80 hover:text-white">
                                <i className="fas fa-times"></i>
                            </button>
                        </div>
                        <div className="p-6">
                            <div className="space-y-3 mb-6">
                                {[
                                    { label: 'Nama', value: selectedItem.nama_lengkap },
                                    { label: 'Kelas', value: selectedItem.kelas },
                                    { label: 'Jenis', value: selectedItem.kategori_label },
                                    { label: 'Keperluan', value: selectedItem.judul },
                                    { label: 'Batas', value: selectedItem.batas_waktu }
                                ].map(row => (
                                    <div key={row.label} className="flex justify-between text-sm py-2 border-b border-slate-50">
                                        <span className="text-slate-400">{row.label}</span>
                                        <span className="font-bold text-slate-800">{row.value}</span>
                                    </div>
                                ))}
                            </div>

                            <div className="mb-6">
                                <label className="block text-sm font-bold text-slate-700 mb-2">Masukkan Kode Konfirmasi</label>
                                <input
                                    type="text"
                                    value={inputKode}
                                    onChange={(e) => setInputKode(e.target.value.toUpperCase())}
                                    className="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-4 text-center text-3xl font-mono font-bold tracking-[8px] focus:outline-none focus:border-blue-500"
                                    placeholder="ABC123"
                                    maxLength={10}
                                />
                            </div>

                            <div className="flex items-center gap-4 mb-6">
                                <div className="flex-1 h-px bg-slate-100"></div>
                                <span className="text-slate-400 text-xs font-bold uppercase">atau</span>
                                <div className="flex-1 h-px bg-slate-100"></div>
                            </div>

                            {isScannerActive ? (
                                <div className="bg-slate-50 rounded-xl p-3 mb-6 relative border border-slate-200">
                                    <div id="qr-reader" className="w-full rounded-lg overflow-hidden"></div>
                                    <button
                                        onClick={stopScanner}
                                        className="mt-3 w-full p-2 text-xs font-bold text-red-500 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100"
                                    >
                                        Tutup Kamera
                                    </button>
                                </div>
                            ) : (
                                <button
                                    onClick={startScanner}
                                    className="w-full p-4 border-2 border-dashed border-slate-200 rounded-xl text-slate-400 hover:border-blue-500 hover:text-blue-500 transition-all mb-6"
                                >
                                    <i className="fas fa-qrcode text-2xl mb-1 block"></i>
                                    <span className="text-xs font-bold">Scan QR Code</span>
                                </button>
                            )}

                            <div className="flex gap-3 mt-8">
                                <button
                                    onClick={() => setShowModal(false)}
                                    className="flex-1 p-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    onClick={submitKonfirmasi}
                                    className="flex-1 p-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition-colors shadow-lg shadow-emerald-500/20"
                                >
                                    Konfirmasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </StandaloneLayout>
    );
}
