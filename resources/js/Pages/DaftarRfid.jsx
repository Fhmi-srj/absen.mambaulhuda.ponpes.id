import React, { useState, useEffect, useRef } from 'react';
import { PageSkeleton } from '../Components/Skeleton';
import LoadingSpinner from '../Components/LoadingSpinner';
import Swal from 'sweetalert2';

export default function DaftarRfid() {
    const [loading, setLoading] = useState(true);
    const [siswaList, setSiswaList] = useState([]);
    const [search, setSearch] = useState('');
    const [hideRegistered, setHideRegistered] = useState(false);
    const [scannedRFID, setScannedRFID] = useState(null);
    const [cardStatus, setCardStatus] = useState({ text: '', type: '' });
    const [selectedSiswa, setSelectedSiswa] = useState(null);
    const [rfidInput, setRfidInput] = useState('');
    const [message, setMessage] = useState({ type: '', text: '' });
    const rfidInputRef = useRef(null);

    useEffect(() => {
        document.title = 'Daftarkan Kartu RFID - Aktivitas Santri';
        fetchSiswa();
    }, []);

    useEffect(() => {
        // Auto focus on RFID input
        if (rfidInputRef.current) rfidInputRef.current.focus();
    }, []);

    const fetchSiswa = async () => {
        try {
            const response = await fetch(`/api/daftar-rfid?search=${encodeURIComponent(search)}`, {
                credentials: 'include',
                headers: { 'Accept': 'application/json' },
            });
            if (response.ok) {
                const data = await response.json();
                setSiswaList(data.siswaList?.data || data.siswaList || []);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleRfidInput = (e) => {
        const val = e.target.value;
        setRfidInput(val);

        // Check if RFID is complete (assuming 10+ digits)
        if (val.length >= 10 && /^\d+$/.test(val)) {
            checkCard(val);
        }
    };

    const checkCard = async (rfidNumber) => {
        try {
            const response = await fetch(`/api/rfid/check?rfid=${encodeURIComponent(rfidNumber)}`, {
                credentials: 'include',
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();

            if (data.registered) {
                setCardStatus({ text: `Sudah terdaftar: ${data.siswa_name}`, type: 'registered' });
                setScannedRFID(null);
            } else {
                setCardStatus({ text: 'Belum terdaftar - Siap digunakan', type: 'available' });
                setScannedRFID(rfidNumber);
            }
        } catch (error) {
            setCardStatus({ text: 'Kartu baru - Siap didaftarkan', type: 'available' });
            setScannedRFID(rfidNumber);
        }
        setRfidInput('');
    };

    const handleSelectSiswa = (siswa) => {
        const hasCard = !!siswa.nomor_rfid;
        const isComplete = siswa.nisn && siswa.kelas;
        if (hasCard || !isComplete) return;

        setSelectedSiswa(siswa);
    };

    const handleRegister = async () => {
        const result = await Swal.fire({
            title: 'Daftarkan Kartu?',
            text: `Daftarkan kartu ${scannedRFID} ke santri ${selectedSiswa.nama_lengkap}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Daftarkan!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const formData = new FormData();
            formData.append('siswa_id', selectedSiswa.id);
            formData.append('rfid', scannedRFID);

            const response = await fetch('/api/rfid/register', {
                method: 'POST',
                body: formData,
                credentials: 'include',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });

            const data = await response.json();
            if (data.success) {
                setMessage({ type: 'success', text: data.message });
                setScannedRFID(null);
                setSelectedSiswa(null);
                setCardStatus({ text: '', type: '' });
                fetchSiswa();
            } else {
                setMessage({ type: 'error', text: data.message });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Gagal mendaftarkan kartu' });
        }
    };

    const filteredSiswa = siswaList.filter(s => {
        if (hideRegistered && s.nomor_rfid) return false;
        if (search) {
            const searchLower = search.toLowerCase();
            return (s.nama_lengkap?.toLowerCase().includes(searchLower) ||
                s.nisn?.toLowerCase().includes(searchLower) ||
                s.kelas?.toLowerCase().includes(searchLower));
        }
        return true;
    });

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>


            {message.text && (
                <div className={`mb-4 p-4 rounded-lg ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {message.text}
                    <button onClick={() => setMessage({ type: '', text: '' })} className="float-right font-bold">&times;</button>
                </div>
            )}

            <div className="grid lg:grid-cols-5 gap-6">
                {/* Left Panel - Card Scanner */}
                <div className="lg:col-span-2">
                    <div className="bg-gradient-to-br from-slate-700 to-slate-900 text-white rounded-2xl p-6 text-center sticky top-24">
                        <div className="mb-4">
                            <LoadingSpinner size="large" />
                        </div>
                        <h4 className="text-xl font-bold mb-2">Tempelkan Kartu</h4>
                        <p className="text-white/70 mb-4">Arahkan kartu baru ke reader</p>

                        <input
                            ref={rfidInputRef}
                            type="text"
                            value={rfidInput}
                            onChange={handleRfidInput}
                            placeholder="Menunggu kartu..."
                            className="w-full px-4 py-3 bg-white/15 border-2 border-white/30 rounded-xl text-center text-xl tracking-widest font-mono focus:border-green-500 focus:outline-none"
                            autoComplete="off"
                        />

                        {scannedRFID && (
                            <div className="mt-4 p-4 bg-green-500/20 border-2 border-green-500 rounded-xl">
                                <div className="text-sm mb-1"><i className="fas fa-credit-card mr-2"></i>Nomor Kartu</div>
                                <div className="text-2xl font-mono font-bold tracking-wider">{scannedRFID}</div>
                                <div className={`mt-2 px-3 py-1 inline-block rounded-full text-sm ${cardStatus.type === 'available' ? 'bg-green-500' : 'bg-amber-500'
                                    }`}>
                                    {cardStatus.text}
                                </div>
                            </div>
                        )}

                        <button
                            onClick={handleRegister}
                            disabled={!scannedRFID || !selectedSiswa}
                            className="w-full mt-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 disabled:from-gray-500 disabled:to-gray-600 disabled:cursor-not-allowed transition-all"
                        >
                            <i className="fas fa-link mr-2"></i>Daftarkan Kartu ke Santri
                        </button>
                    </div>
                </div>

                {/* Right Panel - Student List */}
                <div className="lg:col-span-3">
                    <div className="bg-white rounded-2xl shadow-sm p-6">
                        <div className="flex justify-between items-center mb-4">
                            <h5 className="font-bold text-gray-800"><i className="fas fa-users mr-2"></i>Pilih Santri</h5>
                            <span className="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold">
                                {selectedSiswa ? '1 dipilih' : '0 dipilih'}
                            </span>
                        </div>

                        <div className="flex gap-3 mb-4">
                            <div className="flex-1 relative">
                                <i className="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Cari nama, NISN, atau kelas..."
                                    className="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg"
                                />
                            </div>
                        </div>

                        <label className="flex items-center gap-2 mb-4 cursor-pointer">
                            <input
                                type="checkbox"
                                checked={hideRegistered}
                                onChange={(e) => setHideRegistered(e.target.checked)}
                                className="w-4 h-4 accent-blue-500"
                            />
                            <span className="text-sm text-gray-600">Sembunyikan yang sudah punya kartu</span>
                        </label>

                        <div className="max-h-96 overflow-y-auto border border-gray-200 rounded-xl">
                            {filteredSiswa.length === 0 ? (
                                <div className="text-center text-gray-400 py-8">
                                    <i className="fas fa-users fa-2x mb-2"></i>
                                    <p>Tidak ada data santri</p>
                                </div>
                            ) : (
                                filteredSiswa.map((s) => {
                                    const hasCard = !!s.nomor_rfid;
                                    const isComplete = s.nisn && s.kelas;
                                    const isDisabled = hasCard || !isComplete;
                                    const isSelected = selectedSiswa?.id === s.id;

                                    return (
                                        <div
                                            key={s.id}
                                            onClick={() => handleSelectSiswa(s)}
                                            className={`flex items-center p-4 border-b border-gray-100 last:border-0 cursor-pointer transition-colors ${isDisabled ? 'opacity-50 cursor-not-allowed' :
                                                isSelected ? 'bg-green-50 border-l-4 border-l-green-500' :
                                                    'hover:bg-blue-50'
                                                }`}
                                        >
                                            <div className="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg mr-4 flex-shrink-0">
                                                {s.nama_lengkap?.[0]?.toUpperCase() || 'S'}
                                            </div>
                                            <div className="flex-1">
                                                <div className="font-semibold text-gray-800">{s.nama_lengkap}</div>
                                                <div className="text-sm text-gray-500">
                                                    Kelas {s.kelas || '-'} | {s.nisn || 'Belum ada NISN'}
                                                </div>
                                            </div>
                                            {hasCard ? (
                                                <span className="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg">
                                                    <i className="fas fa-check-circle mr-1"></i>Sudah ada
                                                </span>
                                            ) : !isComplete ? (
                                                <span className="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg">
                                                    <i className="fas fa-exclamation-triangle mr-1"></i>Data belum lengkap
                                                </span>
                                            ) : (
                                                <span className="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                                    <i className="fas fa-plus-circle mr-1"></i>Siap didaftarkan
                                                </span>
                                            )}
                                        </div>
                                    );
                                })
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
