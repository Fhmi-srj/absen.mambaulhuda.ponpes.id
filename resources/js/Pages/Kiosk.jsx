import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';

// Theme (fixed - light only)
const t = {
    bg: 'bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100',
    text: 'text-slate-800',
    card: 'bg-white/80 backdrop-blur-md border-slate-200 shadow-xl',
    cardInner: 'bg-slate-100/80 border-slate-200',
    input: 'bg-white border-slate-300 text-slate-800 placeholder:text-slate-400',
    inputFocus: 'focus:border-blue-500 focus:ring-blue-500/30',
    select: 'bg-white border-slate-300 text-slate-800',
    selectOption: 'bg-white',
    filterInput: 'bg-white border-slate-200 text-slate-800',
    filterSelect: 'bg-white border-slate-200 text-slate-800',
    attendanceItem: 'bg-white border-slate-100 shadow-sm',
    dropdown: 'bg-white border-slate-200 shadow-xl',
    dropdownHover: 'hover:bg-blue-50',
    dropdownBorder: 'border-slate-100',
    subtext: 'text-slate-500',
    clockText: 'text-slate-700',
    emptyState: 'text-slate-400',
    feedbackSuccess: 'bg-emerald-50 border-emerald-300',
    feedbackError: 'bg-red-50 border-red-300',
    feedbackSuccessText: 'text-emerald-600',
    feedbackErrorText: 'text-red-600',
    modal: 'bg-black/40',
    modalCard: 'bg-white border-slate-200 shadow-2xl',
    modalInput: 'bg-slate-50 border-slate-300',
    resetBtn: 'bg-red-100 border-red-200 hover:bg-red-200 text-red-600',
    rosterAlpha: 'bg-red-50 border-red-200 hover:bg-red-100 shadow-sm',
    rosterHadir: 'bg-emerald-50 border-emerald-200 shadow-sm',
    rosterTerlambat: 'bg-amber-50 border-amber-200 shadow-sm',
};

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


    // Mode state
    const [mode, setMode] = useState('rfid');

    // Manual mode - roster states
    const [kelasList, setKelasList] = useState([]);
    const [selectedKelas, setSelectedKelas] = useState('');
    const [roster, setRoster] = useState([]);
    const [rosterSummary, setRosterSummary] = useState({ total: 0, hadir: 0, terlambat: 0, alpha: 0 });
    const [loadingRoster, setLoadingRoster] = useState(false);
    const [confirmModal, setConfirmModal] = useState(null); // selected student for confirmation
    const [manualFeedback, setManualFeedback] = useState(null);
    const [modalDate, setModalDate] = useState('');
    const [modalTime, setModalTime] = useState('');

    const rfidInputRef = useRef(null);

    useEffect(() => {
        document.title = 'Absensi RFID Kiosk - Aktivitas Santri';
        fetchData();
        const clockInterval = setInterval(() => setCurrentTime(new Date()), 1000);
        const pollInterval = setInterval(fetchData, 10000);
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
            setKelasList(response.data.kelasList || []);
        } catch (error) {
            console.error('Error fetching kiosk data:', error);
        }
    };

    // Fetch roster when jadwal or kelas changes
    const fetchRoster = async (jadwalId, kelas) => {
        if (!jadwalId) return;
        setLoadingRoster(true);
        try {
            const params = new URLSearchParams({ jadwal_id: jadwalId });
            if (kelas) params.append('kelas', kelas);
            const res = await axios.get(`/api/public/kios/roster?${params}`);
            setRoster(res.data.roster || []);
            setRosterSummary(res.data.summary || { total: 0, hadir: 0, terlambat: 0, alpha: 0 });
        } catch (err) {
            console.error('Error fetching roster:', err);
        } finally {
            setLoadingRoster(false);
        }
    };

    useEffect(() => {
        if (mode === 'manual' && selectedJadwal) {
            fetchRoster(selectedJadwal.id, selectedKelas);
        }
    }, [mode, selectedJadwal, selectedKelas]);

    useEffect(() => {
        const handleKeyDown = () => {
            if (!showPasswordModal && !confirmModal && mode === 'rfid' && rfidInputRef.current) {
                rfidInputRef.current.focus();
            }
        };
        document.addEventListener('keydown', handleKeyDown);
        return () => document.removeEventListener('keydown', handleKeyDown);
    }, [showPasswordModal, confirmModal, mode]);

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

    // Open confirm modal for a student and pre-fill current date/time
    const openConfirmModal = (student) => {
        const now = new Date();
        setModalDate(now.toISOString().split('T')[0]);
        setModalTime(now.toTimeString().substring(0, 5));
        setConfirmModal(student);
    };

    // Confirm attendance for a student from the roster
    const handleConfirmAttendance = async (student) => {
        setIsProcessing(true);
        try {
            const response = await axios.post('/api/public/attendance/manual', {
                siswa_id: student.id,
                jadwal_id: selectedJadwal.id,
                attendance_date: modalDate,
                attendance_time: modalTime
            });
            if (response.data.success) {
                setManualFeedback({
                    success: true,
                    name: student.nama_lengkap,
                    message: response.data.message,
                    status: response.data.status
                });
                // Refresh roster
                fetchRoster(selectedJadwal.id, selectedKelas);
                fetchData();
            } else {
                setManualFeedback({
                    success: false,
                    name: student.nama_lengkap,
                    message: response.data.message || 'Gagal absen'
                });
            }
        } catch (error) {
            setManualFeedback({
                success: false,
                name: student.nama_lengkap,
                message: 'Error: ' + (error.response?.data?.message || error.message)
            });
        } finally {
            setIsProcessing(false);
            setConfirmModal(null);
            setTimeout(() => setManualFeedback(null), 3000);
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

    const getStatusStyle = (status) => {
        if (status === 'hadir') return t.rosterHadir;
        if (status === 'terlambat') return t.rosterTerlambat;
        return t.rosterAlpha;
    };

    const getStatusBadge = (status) => {
        if (status === 'hadir') return 'bg-emerald-500 text-white';
        if (status === 'terlambat') return 'bg-amber-500 text-white';
        return 'bg-red-500 text-white';
    };

    return (
        <div>
            <div className={`min-h-[calc(100vh-120px)] overflow-y-auto grid grid-cols-1 lg:grid-cols-[1fr_1.5fr] gap-4 lg:gap-8 p-4 lg:p-6 transition-colors duration-500 rounded-2xl ${t.bg} ${t.text}`}>
                {/* Theme Toggle - floating button */}
                <button
                    onClick={toggleTheme}
                    className={`fixed top-4 left-4 z-40 w-12 h-12 rounded-full border flex items-center justify-center transition-all duration-300 shadow-lg ${t.toggleBtn}`}
                    title={theme === 'dark' ? 'Ganti ke Tema Terang' : 'Ganti ke Tema Gelap'}
                >
                    <i className={t.toggleIcon}></i>
                </button>

                {/* Left Panel */}
                <div className="flex flex-col justify-center items-center text-center">
                    <div className={`rounded-3xl p-8 lg:p-12 w-full max-w-md border shadow-2xl transition-colors duration-500 ${t.card}`}>

                        {/* Mode Tabs */}
                        <div className={`flex rounded-2xl p-1.5 mb-8 border transition-colors duration-500 ${t.cardInner}`}>
                            <button
                                onClick={() => setMode('rfid')}
                                className={`flex-1 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2 ${mode === 'rfid'
                                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30'
                                    : `${t.subtext} hover:opacity-80`
                                    }`}
                            >
                                <i className="fas fa-id-card"></i>
                                RFID
                            </button>
                            <button
                                onClick={() => setMode('manual')}
                                className={`flex-1 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2 ${mode === 'manual'
                                    ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30'
                                    : `${t.subtext} hover:opacity-80`
                                    }`}
                            >
                                <i className="fas fa-users"></i>
                                Manual
                            </button>
                        </div>

                        {/* RFID Mode */}
                        {mode === 'rfid' && (
                            <div>
                                <div className="text-8xl mb-6 text-blue-400 animate-pulse">
                                    <i className="fas fa-id-card"></i>
                                </div>
                                <h1 className="text-3xl font-bold mb-2">Tempelkan Kartu</h1>
                                <p className={`${t.subtext} mb-8`}>Arahkan kartu RFID ke reader</p>

                                <form onSubmit={handleRfidSubmit} autoComplete="off">
                                    <input
                                        ref={rfidInputRef}
                                        type="text"
                                        value={inputRfid}
                                        onChange={(e) => setInputRfid(e.target.value)}
                                        className={`w-full border-2 rounded-2xl p-5 text-3xl text-center font-mono font-bold tracking-[8px] focus:outline-none focus:ring-4 transition-all placeholder:tracking-normal placeholder:text-lg placeholder:font-sans ${t.input} ${t.inputFocus}`}
                                        placeholder={isProcessing ? "Memproses..." : "Menunggu kartu..."}
                                        autoFocus
                                    />
                                </form>
                            </div>
                        )}

                        {/* Manual Mode - Simple instruction */}
                        {mode === 'manual' && (
                            <div>
                                <div className="text-6xl mb-4 text-emerald-400">
                                    <i className="fas fa-users"></i>
                                </div>
                                <h1 className="text-2xl font-bold mb-2">Absensi Manual</h1>
                                <p className={`${t.subtext} mb-4 text-sm`}>Klik santri di panel sebelah kanan untuk konfirmasi kehadiran</p>

                                {/* Feedback */}
                                {manualFeedback && (
                                    <div className={`p-3 rounded-2xl border-2 ${manualFeedback.success ? t.feedbackSuccess : t.feedbackError}`}>
                                        <div className="text-2xl mb-1">
                                            {manualFeedback.success ? (
                                                <i className="fas fa-check-circle text-emerald-400"></i>
                                            ) : (
                                                <i className="fas fa-times-circle text-red-400"></i>
                                            )}
                                        </div>
                                        <div className="font-bold">{manualFeedback.name}</div>
                                        <div className={`text-sm ${manualFeedback.success ? t.feedbackSuccessText : t.feedbackErrorText}`}>
                                            {manualFeedback.message}
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Jadwal Selector */}
                        <div className="mt-8">
                            <select
                                value={selectedJadwal?.id || ''}
                                onChange={handleJadwalChange}
                                className={`w-full p-4 rounded-xl border-2 font-bold text-lg cursor-pointer focus:outline-none focus:border-emerald-500 transition-colors duration-500 ${t.select}`}
                            >
                                <option value="" className={t.selectOption}>-- Pilih Jenis Absen --</option>
                                {jadwalList.map(j => (
                                    <option key={j.id} value={j.id} className={t.selectOption}>
                                        {j.name} {j.start_time ? `(${j.start_time.substring(0, 5)})` : ''}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>
                </div>

                {/* Right Panel */}
                <div className="flex flex-col h-full overflow-hidden">
                    {mode === 'manual' ? (
                        /* Roster Panel for Manual Mode */
                        <>
                            <div className="flex justify-between items-center mb-4">
                                <div className="text-2xl font-bold flex items-center">
                                    <i className="fas fa-users mr-3 text-emerald-400"></i>
                                    Daftar Santri
                                </div>
                                <div className="flex gap-4">
                                    <div className="text-center">
                                        <div className="text-3xl font-bold text-emerald-400">{rosterSummary.hadir}</div>
                                        <div className={`text-[10px] uppercase tracking-wider font-bold ${t.subtext}`}>Hadir</div>
                                    </div>
                                    <div className="text-center">
                                        <div className="text-3xl font-bold text-amber-400">{rosterSummary.terlambat}</div>
                                        <div className={`text-[10px] uppercase tracking-wider font-bold ${t.subtext}`}>Terlambat</div>
                                    </div>
                                    <div className="text-center">
                                        <div className="text-3xl font-bold text-red-400">{rosterSummary.alpha}</div>
                                        <div className={`text-[10px] uppercase tracking-wider font-bold ${t.subtext}`}>Alpha</div>
                                    </div>
                                </div>
                            </div>

                            {/* Class filter + search */}
                            <div className={`rounded-2xl p-4 mb-4 backdrop-blur-sm border transition-colors duration-500 ${t.cardInner}`}>
                                <div className="flex gap-3 flex-wrap">
                                    <select
                                        value={selectedKelas}
                                        onChange={(e) => setSelectedKelas(e.target.value)}
                                        className={`flex-1 min-w-[150px] border rounded-lg p-2 text-sm font-bold focus:outline-none focus:border-emerald-500 transition-colors duration-500 ${t.filterSelect}`}
                                    >
                                        <option value="" className={t.selectOption}>Semua Kelas</option>
                                        {kelasList.map(k => (
                                            <option key={k} value={k} className={t.selectOption}>{k}</option>
                                        ))}
                                    </select>
                                    <input
                                        type="text"
                                        placeholder="Cari nama..."
                                        value={filters.search}
                                        onChange={(e) => setFilters({ ...filters, search: e.target.value })}
                                        className={`flex-1 min-w-[150px] border rounded-lg p-2 text-sm focus:outline-none focus:border-emerald-500 transition-colors duration-500 ${t.filterInput}`}
                                    />
                                    <button
                                        onClick={() => { setFilters({ search: '', gender: '', status: '' }); setSelectedKelas(''); }}
                                        className={`border rounded-lg px-3 transition-colors ${t.resetBtn}`}
                                    >
                                        <i className="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            {/* Student Roster */}
                            {!selectedJadwal ? (
                                <div className={`flex flex-col items-center justify-center flex-1 opacity-50 ${t.emptyState}`}>
                                    <i className="fas fa-hand-point-left text-6xl mb-4"></i>
                                    <p className="text-xl font-bold">Pilih jadwal terlebih dahulu</p>
                                    <p className="text-sm mt-2">Pilih jenis absen di panel sebelah kiri</p>
                                </div>
                            ) : loadingRoster ? (
                                <div className="flex items-center justify-center flex-1">
                                    <i className="fas fa-spinner fa-spin text-4xl text-emerald-400"></i>
                                </div>
                            ) : (
                                <div className="flex-1 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
                                    <div className="space-y-2">
                                        {roster
                                            .filter(s => filters.search === '' || s.nama_lengkap.toLowerCase().includes(filters.search.toLowerCase()))
                                            .map(s => (
                                                <button
                                                    key={s.id}
                                                    onClick={() => s.status === 'alpha' && openConfirmModal(s)}
                                                    disabled={s.status !== 'alpha'}
                                                    className={`w-full flex items-center gap-3 p-4 rounded-xl border transition-all text-left ${getStatusStyle(s.status)} ${s.status === 'alpha' ? 'cursor-pointer active:scale-[0.99]' : 'cursor-default opacity-70'}`}
                                                >
                                                    <div className={`w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0 ${s.status === 'alpha' ? 'bg-red-500' : s.status === 'hadir' ? 'bg-emerald-500' : 'bg-amber-500'}`}>
                                                        {s.status === 'alpha' ? <i className="fas fa-times"></i> : <i className="fas fa-check"></i>}
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-bold text-sm truncate">{s.nama_lengkap}</div>
                                                        <div className={`text-xs ${t.subtext}`}>{s.kelas} {s.nisn ? `• ${s.nisn}` : ''}</div>
                                                    </div>
                                                    <span className={`text-[9px] uppercase font-black px-2 py-0.5 rounded-full flex-shrink-0 ${getStatusBadge(s.status)}`}>
                                                        {s.status === 'terlambat' && s.days_late != null
                                                            ? `Terlambat ${s.days_late} hari`
                                                            : s.status}
                                                    </span>
                                                </button>
                                            ))}
                                    </div>
                                    {roster.filter(s => filters.search === '' || s.nama_lengkap.toLowerCase().includes(filters.search.toLowerCase())).length === 0 && (
                                        <div className={`text-center py-12 ${t.emptyState}`}>
                                            <i className="fas fa-search text-4xl mb-3"></i>
                                            <p>Tidak ada santri ditemukan</p>
                                        </div>
                                    )}
                                </div>
                            )}
                        </>
                    ) : (
                        /* Live Attendance Panel for RFID Mode */
                        <>
                            <div className="flex justify-between items-center mb-6">
                                <div className="text-2xl font-bold flex items-center">
                                    <i className="fas fa-broadcast-tower mr-3 text-blue-400"></i>
                                    Live Attendance
                                </div>
                                <div className="flex gap-8">
                                    <div className="text-center">
                                        <div className="text-4xl font-bold text-emerald-400">{stats.todayTotal}</div>
                                        <div className={`text-xs uppercase tracking-wider ${t.subtext}`}>Hadir Hari Ini</div>
                                    </div>
                                    <div className="text-center">
                                        <div className="text-4xl font-bold text-blue-400">{stats.totalSiswa}</div>
                                        <div className={`text-xs uppercase tracking-wider ${t.subtext}`}>Total Santri</div>
                                    </div>
                                </div>
                            </div>

                            <div className="text-center mb-8">
                                <div className={`text-6xl font-bold font-mono tracking-tighter ${t.clockText}`}>
                                    {currentTime.toLocaleTimeString('id-ID', { hour12: false })}
                                </div>
                                <div className={`text-lg mt-2 ${t.subtext}`}>
                                    {currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                                </div>
                            </div>

                            <div className={`rounded-2xl p-4 mb-4 backdrop-blur-sm border transition-colors duration-500 ${t.cardInner}`}>
                                <div className="flex gap-3 flex-wrap">
                                    <input
                                        type="text"
                                        placeholder="Cari nama..."
                                        value={filters.search}
                                        onChange={(e) => setFilters({ ...filters, search: e.target.value })}
                                        className={`flex-1 min-w-[150px] border rounded-lg p-2 text-sm focus:outline-none focus:border-blue-500 transition-colors duration-500 ${t.filterInput}`}
                                    />
                                    <select
                                        value={filters.gender}
                                        onChange={(e) => setFilters({ ...filters, gender: e.target.value })}
                                        className={`border rounded-lg p-2 text-sm focus:outline-none transition-colors duration-500 ${t.filterSelect}`}
                                    >
                                        <option value="" className={t.selectOption}>Semua JK</option>
                                        <option value="L" className={t.selectOption}>Laki-laki</option>
                                        <option value="P" className={t.selectOption}>Perempuan</option>
                                    </select>
                                    <select
                                        value={filters.status}
                                        onChange={(e) => setFilters({ ...filters, status: e.target.value })}
                                        className={`border rounded-lg p-2 text-sm focus:outline-none transition-colors duration-500 ${t.filterSelect}`}
                                    >
                                        <option value="" className={t.selectOption}>Semua Status</option>
                                        <option value="hadir" className={t.selectOption}>Hadir</option>
                                        <option value="terlambat" className={t.selectOption}>Terlambat</option>
                                        <option value="pulang" className={t.selectOption}>Pulang</option>
                                    </select>
                                    <button
                                        onClick={() => setFilters({ search: '', gender: '', status: '' })}
                                        className={`border rounded-lg px-3 transition-colors ${t.resetBtn}`}
                                    >
                                        <i className="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div className="flex-1 overflow-y-auto space-y-3 pr-2 scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
                                {filteredAttendances.length > 0 ? (
                                    filteredAttendances.map((item) => (
                                        <div key={item.id} className={`flex items-center p-4 rounded-xl border transition-colors duration-500 ${t.attendanceItem}`}>
                                            <div className="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl font-bold mr-4 shadow-lg shadow-blue-500/20 text-white">
                                                {(item.nama_lengkap || '?').substring(0, 1).toUpperCase()}
                                            </div>
                                            <div className="flex-1">
                                                <div className="font-bold text-lg">{item.nama_lengkap}</div>
                                                <div className={`text-sm ${t.subtext}`}>
                                                    Kelas {item.kelas} | {item.nomor_induk || '-'}
                                                </div>
                                            </div>
                                            <div className="text-right">
                                                <div className="text-xl font-bold font-mono">
                                                    {item.attendance_time ? item.attendance_time.substring(0, 5) : '-'}
                                                </div>
                                                <span className={`text-[10px] uppercase font-black px-2 py-0.5 rounded-full text-white ${item.status === 'hadir' ? 'bg-emerald-500' :
                                                    item.status === 'terlambat' ? 'bg-amber-500' : 'bg-blue-500'
                                                    }`}>
                                                    {item.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className={`flex flex-col items-center justify-center p-12 opacity-50 ${t.emptyState}`}>
                                        <i className="fas fa-inbox text-6xl mb-4"></i>
                                        <p className="text-xl">Belum ada absensi hari ini</p>
                                    </div>
                                )}
                            </div>
                        </>
                    )}
                </div>
            </div>

            {/* Confirm Attendance Modal */}
            {confirmModal && (
                <div className={`fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm ${t.modal}`} onClick={() => setConfirmModal(null)}>
                    <div className={`border rounded-2xl p-8 w-full max-w-sm shadow-2xl ${t.modalCard} ${t.text}`} onClick={(e) => e.stopPropagation()}>
                        <div className="text-center mb-6">
                            <div className="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4 shadow-lg shadow-emerald-500/30">
                                {confirmModal.nama_lengkap?.charAt(0)}
                            </div>
                            <h2 className="text-xl font-bold">{confirmModal.nama_lengkap}</h2>
                            <div className={`text-sm mt-1 ${t.subtext}`}>
                                {confirmModal.kelas} {confirmModal.nisn ? `• ${confirmModal.nisn}` : ''}
                            </div>
                        </div>

                        <div className={`rounded-xl p-4 mb-4 border ${t.cardInner}`}>
                            <div className="flex justify-between items-center mb-3">
                                <span className={`text-sm ${t.subtext}`}>Jadwal</span>
                                <span className="font-bold text-sm">{selectedJadwal?.name}</span>
                            </div>
                            <div className="flex justify-between items-center">
                                <span className={`text-sm ${t.subtext}`}>Status Saat Ini</span>
                                <span className="text-xs uppercase font-black px-2 py-0.5 rounded-full bg-red-500 text-white">Alpha</span>
                            </div>
                        </div>

                        {/* Custom date + time */}
                        <div className="mb-4">
                            <label className={`block text-xs font-bold mb-1 ${t.subtext}`}>Tanggal Kehadiran</label>
                            <input
                                type="date"
                                value={modalDate}
                                onChange={(e) => setModalDate(e.target.value)}
                                className={`w-full border-2 rounded-xl px-4 py-2 text-sm font-mono focus:outline-none focus:border-emerald-500 ${t.modalInput} ${t.text}`}
                            />
                        </div>
                        <div className="mb-6">
                            <label className={`block text-xs font-bold mb-1 ${t.subtext}`}>Waktu Kehadiran</label>
                            <input
                                type="time"
                                value={modalTime}
                                onChange={(e) => setModalTime(e.target.value)}
                                className={`w-full border-2 rounded-xl px-4 py-2 text-sm font-mono focus:outline-none focus:border-emerald-500 ${t.modalInput} ${t.text}`}
                            />
                        </div>

                        <div className="flex gap-3">
                            <button
                                onClick={() => setConfirmModal(null)}
                                className={`flex-1 p-3 rounded-xl font-bold transition-colors ${theme === 'dark' ? 'bg-slate-700 hover:bg-slate-600' : 'bg-slate-200 hover:bg-slate-300'}`}
                            >
                                Batal
                            </button>
                            <button
                                onClick={() => handleConfirmAttendance(confirmModal)}
                                disabled={isProcessing}
                                className="flex-1 p-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 font-bold transition-colors text-white disabled:opacity-50"
                            >
                                {isProcessing ? (
                                    <i className="fas fa-spinner fa-spin"></i>
                                ) : (
                                    <><i className="fas fa-check mr-2"></i>Hadir</>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Password Modal */}
            {showPasswordModal && (
                <div className={`fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm ${t.modal}`}>
                    <div className={`border rounded-2xl p-8 w-full max-w-sm shadow-2xl ${t.modalCard} ${t.text}`}>
                        <div className="flex items-center mb-6">
                            <i className="fas fa-lock mr-3 text-blue-400 text-xl"></i>
                            <h2 className="text-xl font-bold">Masukkan Sandi</h2>
                        </div>
                        <p className={`text-sm mb-6 ${t.subtext}`}>
                            Untuk mengubah jenis absensi, masukkan sandi kiosk:
                        </p>
                        <input
                            type="password"
                            value={passwordInput}
                            onChange={(e) => setPasswordInput(e.target.value)}
                            onKeyUp={(e) => e.key === 'Enter' && verifyPassword()}
                            autoFocus
                            className={`w-full border-2 rounded-xl p-4 text-center text-2xl tracking-widest focus:outline-none focus:border-blue-500 ${t.modalInput} ${t.text}`}
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
                                className={`flex-1 p-3 rounded-xl font-bold transition-colors ${theme === 'dark' ? 'bg-slate-700 hover:bg-slate-600' : 'bg-slate-200 hover:bg-slate-300'}`}
                            >
                                Batal
                            </button>
                            <button
                                onClick={verifyPassword}
                                className="flex-1 p-3 rounded-xl bg-blue-600 hover:bg-blue-500 font-bold transition-colors text-white"
                            >
                                Konfirmasi
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
