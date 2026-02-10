import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import StandaloneLayout from '@/Layouts/StandaloneLayout';

export default function Kiosk() {
    const [jadwalList, setJadwalList] = useState([]);
    const [recentAttendances, setRecentAttendances] = useState([]);
    const [stats, setStats] = useState({ todayTotal: 0, totalSiswa: 0 });
    const [selectedJadwal, setSelectedJadwal] = useState(null);
    const [kioskPassword, setKioskPassword] = useState('');
    const [inputRfid, setInputRfid] = useState('');
    const [currentTime, setCurrentTime] = useState(new Date());
    const [filters, setFilters] = useState({ search: '', gender: '', status: '' });
    const [isProcessing, setIsProcessing] = useState(false);
    const [showPasswordModal, setShowPasswordModal] = useState(false);
    const [pendingJadwal, setPendingJadwal] = useState(null);
    const [passwordInput, setPasswordInput] = useState('');
    const [passwordError, setPasswordError] = useState(false);

    const rfidInputRef = useRef(null);

    useEffect(() => {
        document.title = 'Absensi RFID Kiosk - Aktivitas Santri';
        fetchData();
        const clockInterval = setInterval(() => setCurrentTime(new Date()), 1000);
        const pollInterval = setInterval(fetchData, 5000);
        return () => {
            clearInterval(clockInterval);
            clearInterval(pollInterval);
        };
    }, []);

    const fetchData = async () => {
        try {
            const response = await axios.get('/kios');
            setJadwalList(response.data.jadwalList);
            setRecentAttendances(response.data.recentAttendances);
            setStats({
                todayTotal: response.data.todayTotal,
                totalSiswa: response.data.totalSiswa
            });
            setKioskPassword(response.data.kioskPassword);
        } catch (error) {
            console.error('Error fetching kiosk data:', error);
        }
    };

    useEffect(() => {
        const handleKeyDown = () => {
            if (!showPasswordModal && rfidInputRef.current) {
                rfidInputRef.current.focus();
            }
        };
        document.addEventListener('keydown', handleKeyDown);
        return () => document.removeEventListener('keydown', handleKeyDown);
    }, [showPasswordModal]);

    const handleRfidSubmit = async (e) => {
        e.preventDefault();
        if (!selectedJadwal) {
            alert('Pilih jenis absen terlebih dahulu!');
            setInputRfid('');
            return;
        }
        if (isProcessing || !inputRfid) return;

        setIsProcessing(true);
        try {
            const response = await axios.post('/api/attendance/rfid', {
                rfid: inputRfid,
                jadwal_id: selectedJadwal.id
            });
            if (response.data.success) {
                fetchData();
                setInputRfid('');
            } else {
                alert(response.data.message || 'Gagal absen');
            }
        } catch (error) {
            alert('Error: ' + (error.response?.data?.message || error.message));
        } finally {
            setIsProcessing(false);
        }
    };

    const handleJadwalChange = (e) => {
        const id = e.target.value;
        const jadwal = jadwalList.find(j => j.id == id);

        if (!selectedJadwal) {
            setSelectedJadwal(jadwal);
            return;
        }

        if (selectedJadwal.id == id) return;

        setPendingJadwal(jadwal);
        setShowPasswordModal(true);
        setPasswordInput('');
        setPasswordError(false);
    };

    const verifyPassword = () => {
        if (passwordInput === kioskPassword) {
            setSelectedJadwal(pendingJadwal);
            setShowPasswordModal(false);
            setPendingJadwal(null);
        } else {
            setPasswordError(true);
        }
    };

    const filteredAttendances = recentAttendances.filter(item => {
        return (
            (filters.search === '' || item.nama_lengkap.toLowerCase().includes(filters.search.toLowerCase())) &&
            (filters.gender === '' || item.jenis_kelamin === filters.gender) &&
            (filters.status === '' || item.status === filters.status)
        );
    });

    return (
        <StandaloneLayout>
            <div className="h-screen grid grid-cols-1 lg:grid-cols-[1fr_1.5fr] gap-8 p-8 overflow-hidden bg-slate-900 text-white">
                {/* RFID Panel */}
                <div className="flex flex-col justify-center items-center text-center">
                    <div className="bg-white/10 backdrop-blur-md rounded-3xl p-12 w-full max-w-md border border-white/20 shadow-2xl">
                        <div className="text-8xl mb-6 text-blue-400 animate-pulse">
                            <i className="fas fa-id-card"></i>
                        </div>
                        <h1 className="text-3xl font-bold mb-2">Tempelkan Kartu</h1>
                        <p className="text-slate-400 mb-8">Arahkan kartu RFID ke reader</p>

                        <form onSubmit={handleRfidSubmit} autoComplete="off">
                            <input
                                ref={rfidInputRef}
                                type="text"
                                value={inputRfid}
                                onChange={(e) => setInputRfid(e.target.value)}
                                className="w-full bg-white/20 border-2 border-white/30 rounded-2xl p-5 text-3xl text-center font-mono font-bold tracking-[8px] focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/30 transition-all placeholder:tracking-normal placeholder:text-lg placeholder:font-sans"
                                placeholder={isProcessing ? "Memproses..." : "Menunggu kartu..."}
                                autoFocus
                            />
                        </form>

                        <div className="mt-8">
                            <select
                                value={selectedJadwal?.id || ''}
                                onChange={handleJadwalChange}
                                className="w-full p-4 rounded-xl border-2 border-white/30 bg-white/10 text-white font-bold text-lg cursor-pointer focus:outline-none focus:border-emerald-500 appearance-none bg-[url('data:image/svg+xml,%3Csvg_xmlns=%22http://www.w3.org/2000/svg%22_width=%2224%22_height=%2224%22_viewBox=%220_0_24_24%22_fill=%22none%22_stroke=%22white%22_stroke-width=%222%22_stroke-linecap=%22round%22_stroke-linejoin=%22round%22%3E%3Cpolyline_points=%226_9_12_15_18_9%22%3E%3C/polyline%3E%3C/svg%3E')] bg-no-repeat bg-[right_1rem_center] bg-[length:1.25rem]"
                            >
                                <option value="" className="bg-slate-800">-- Pilih Jenis Absen --</option>
                                {jadwalList.map(j => (
                                    <option key={j.id} value={j.id} className="bg-slate-800">
                                        {j.name} ({j.start_time.substring(0, 5)})
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>
                </div>

                {/* Live Panel */}
                <div className="flex flex-col h-full overflow-hidden">
                    <div className="flex justify-between items-center mb-6">
                        <div className="text-2xl font-bold flex items-center">
                            <i className="fas fa-broadcast-tower mr-3 text-blue-400"></i>
                            Live Attendance
                        </div>
                        <div className="flex gap-8">
                            <div className="text-center">
                                <div className="text-4xl font-bold text-emerald-400">{stats.todayTotal}</div>
                                <div className="text-xs text-slate-400 uppercase tracking-wider">Hadir Hari Ini</div>
                            </div>
                            <div className="text-center">
                                <div className="text-4xl font-bold text-blue-400">{stats.totalSiswa}</div>
                                <div className="text-xs text-slate-400 uppercase tracking-wider">Total Santri</div>
                            </div>
                        </div>
                    </div>

                    <div className="text-center mb-8">
                        <div className="text-6xl font-bold font-mono tracking-tighter text-blue-100">
                            {currentTime.toLocaleTimeString('id-ID', { hour12: false })}
                        </div>
                        <div className="text-lg text-slate-400 mt-2">
                            {currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                        </div>
                    </div>

                    <div className="bg-white/5 rounded-2xl p-4 mb-4 backdrop-blur-sm border border-white/10">
                        <div className="flex gap-3 flex-wrap">
                            <input
                                type="text"
                                placeholder="Cari nama..."
                                value={filters.search}
                                onChange={(e) => setFilters({ ...filters, search: e.target.value })}
                                className="flex-1 min-w-[150px] bg-white/10 border border-white/20 rounded-lg p-2 text-sm focus:outline-none focus:border-blue-500"
                            />
                            <select
                                value={filters.gender}
                                onChange={(e) => setFilters({ ...filters, gender: e.target.value })}
                                className="bg-white/10 border border-white/20 rounded-lg p-2 text-sm focus:outline-none"
                            >
                                <option value="" className="bg-slate-800">Semua JK</option>
                                <option value="L" className="bg-slate-800">Laki-laki</option>
                                <option value="P" className="bg-slate-800">Perempuan</option>
                            </select>
                            <select
                                value={filters.status}
                                onChange={(e) => setFilters({ ...filters, status: e.target.value })}
                                className="bg-white/10 border border-white/20 rounded-lg p-2 text-sm focus:outline-none"
                            >
                                <option value="" className="bg-slate-800">Semua Status</option>
                                <option value="hadir" className="bg-slate-800">Hadir</option>
                                <option value="terlambat" className="bg-slate-800">Terlambat</option>
                                <option value="pulang" className="bg-slate-800">Pulang</option>
                            </select>
                            <button
                                onClick={() => setFilters({ search: '', gender: '', status: '' })}
                                className="bg-red-500/20 border border-red-500/40 rounded-lg px-3 hover:bg-red-500/40 transition-colors"
                            >
                                <i className="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div className="flex-1 overflow-y-auto space-y-3 pr-2 scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
                        {filteredAttendances.length > 0 ? (
                            filteredAttendances.map((item, index) => (
                                <div key={item.id} className="flex items-center p-4 bg-white/10 rounded-xl border border-white/5 animate-in slide-in-from-left duration-300">
                                    <div className="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl font-bold mr-4 shadow-lg shadow-blue-500/20">
                                        {item.nama_lengkap.substring(0, 1).toUpperCase()}
                                    </div>
                                    <div className="flex-1">
                                        <div className="font-bold text-lg">{item.nama_lengkap}</div>
                                        <div className="text-slate-400 text-sm">
                                            Kelas {item.kelas} | {item.nomor_induk || '-'}
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <div className="text-xl font-bold font-mono">
                                            {item.attendance_time.substring(0, 5)}
                                        </div>
                                        <span className={`text-[10px] uppercase font-black px-2 py-0.5 rounded-full ${item.status === 'hadir' ? 'bg-emerald-500' :
                                            item.status === 'terlambat' ? 'bg-amber-500' : 'bg-blue-500'
                                            }`}>
                                            {item.status}
                                        </span>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="flex flex-col items-center justify-center p-12 text-slate-500 opacity-50">
                                <i className="fas fa-inbox text-6xl mb-4"></i>
                                <p className="text-xl">Belum ada absensi hari ini</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Password Modal */}
            {showPasswordModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                    <div className="bg-slate-800 border border-slate-700 rounded-2xl p-8 w-full max-w-sm shadow-2xl">
                        <div className="flex items-center mb-6">
                            <i className="fas fa-lock mr-3 text-blue-400 text-xl"></i>
                            <h2 className="text-xl font-bold">Masukkan Sandi</h2>
                        </div>
                        <p className="text-slate-400 text-sm mb-6">
                            Untuk mengubah jenis absensi, masukkan sandi kiosk:
                        </p>
                        <input
                            type="password"
                            value={passwordInput}
                            onChange={(e) => setPasswordInput(e.target.value)}
                            onKeyUp={(e) => e.key === 'Enter' && verifyPassword()}
                            autoFocus
                            className="w-full bg-slate-900 border-2 border-slate-700 rounded-xl p-4 text-center text-2xl tracking-widest focus:outline-none focus:border-blue-500"
                            placeholder="****"
                        />
                        {passwordError && (
                            <div className="text-red-400 text-sm mt-3 text-center font-bold">Sandi salah!</div>
                        )}
                        <div className="flex gap-3 mt-8">
                            <button
                                onClick={() => {
                                    setShowPasswordModal(false);
                                    setPendingJadwal(null);
                                }}
                                className="flex-1 p-3 rounded-xl bg-slate-700 hover:bg-slate-600 font-bold transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                onClick={verifyPassword}
                                className="flex-1 p-3 rounded-xl bg-blue-600 hover:bg-blue-500 font-bold transition-colors"
                            >
                                Konfirmasi
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </StandaloneLayout>
    );
}
