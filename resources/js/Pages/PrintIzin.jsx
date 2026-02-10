import React, { useState, useEffect } from 'react';
import { PageSkeleton } from '../Components/Skeleton';

export default function PrintIzin() {
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState('sekolah');
    const [kategori, setKategori] = useState('sakit');
    const [santriList, setSantriList] = useState([]);
    const [selectedSantri, setSelectedSantri] = useState([]);
    const [tujuanGuru, setTujuanGuru] = useState('');
    const [kelas, setKelas] = useState('');
    const [printing, setPrinting] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });

    useEffect(() => {
        document.title = 'Print Izin - Aktivitas Santri';
        loadSantri(kategori);
    }, []);

    const loadSantri = async (kat) => {
        setLoading(true);
        try {
            const response = await fetch(`/api/print-izin?kategori=${kat}`, {
                credentials: 'include',
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();
            if (data.success) {
                setSantriList(data.data || []);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleKategoriChange = (kat) => {
        setKategori(kat);
        setSelectedSantri([]);
        loadSantri(kat);
    };

    const handleSelectSantri = (santri) => {
        const isSelected = selectedSantri.find(s => s.id === santri.siswa_id);

        if (isSelected) {
            setSelectedSantri(prev => prev.filter(s => s.id !== santri.siswa_id));
        } else {
            if (selectedSantri.length >= 5) {
                alert('Maksimal 5 santri');
                return;
            }
            setSelectedSantri(prev => [...prev, {
                id: santri.siswa_id,
                nama: santri.nama_lengkap,
                kelas: santri.kelas
            }]);
            if (selectedSantri.length === 0 && santri.kelas) {
                setKelas(santri.kelas);
            }
        }
    };

    const generatePreview = () => {
        const now = new Date();
        const tanggal = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
        const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        const nomor = `0XX/SKA.001/PPMH/${romanMonths[now.getMonth()]}/${now.getFullYear()}`;
        const alasan = kategori === 'sakit' ? 'Sakit' : 'Izin Pulang';
        const namaList = selectedSantri.length > 0
            ? selectedSantri.map(s => s.nama).join('\n          ')
            : '-';

        return `================================
   PONDOK PESANTREN MAMBA'UL   
           HUDA              
       PAJOMBLANGAN          
================================
      SURAT IZIN SEKOLAH      
   NO: ${nomor}
--------------------------------

Kepada Yth.
Bapak/Ibu Guru ${tujuanGuru || '...'}

Assalamu'alaikum Wr. Wb.

Dengan hormat, melalui surat 
ini kami memberitahukan bahwa:

Nama    : ${namaList}
Kelas   : ${kelas || '-'}
Ket     : Izin tidak mengikuti
          KBM
Tanggal : ${tanggal}
Alasan  : ${alasan}

Demikian surat ini kami 
sampaikan. Atas perhatian 
Bapak/Ibu, kami ucapkan 
terima kasih.

Wassalamu'alaikum Wr. Wb.

Hormat kami,



Pengurus Izin
================================`;
    };

    const handleCetak = async () => {
        if (selectedSantri.length === 0) {
            alert('Pilih minimal 1 santri');
            return;
        }
        if (!tujuanGuru.trim() || !kelas.trim()) {
            alert('Lengkapi field Tujuan Guru dan Kelas');
            return;
        }

        setPrinting(true);
        try {
            const response = await fetch('/api/print-queue', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    job_type: 'surat_izin',
                    kategori,
                    santri_ids: selectedSantri.map(s => s.id),
                    santri_names: selectedSantri.map(s => s.nama),
                    tujuan_guru: tujuanGuru,
                    kelas,
                }),
            });

            const data = await response.json();
            if (data.success) {
                setMessage({ type: 'success', text: `Dikirim ke antrian! No: ${data.nomor_surat || '-'}` });
                setSelectedSantri([]);
                setTujuanGuru('');
                setKelas('');
            } else {
                setMessage({ type: 'error', text: data.message || 'Gagal mengirim' });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Gagal mengirim ke antrian' });
        } finally {
            setPrinting(false);
        }
    };

    const canPrint = selectedSantri.length > 0 && tujuanGuru.trim() && kelas.trim();

    return (
        <>


            {message.text && (
                <div className={`mb-4 p-4 rounded-lg ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {message.text}
                    <button onClick={() => setMessage({ type: '', text: '' })} className="float-right font-bold">&times;</button>
                </div>
            )}

            <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
                {/* Tabs */}
                <div className="bg-gradient-to-r from-green-600 to-green-700 flex">
                    <button
                        onClick={() => setActiveTab('sekolah')}
                        className={`flex-1 py-4 px-6 font-semibold transition-colors ${activeTab === 'sekolah' ? 'bg-white text-green-600' : 'text-white/80 hover:text-white'
                            }`}
                    >
                        <i className="fas fa-school mr-2"></i>Izin Sekolah
                    </button>
                    <button
                        onClick={() => setActiveTab('pondok')}
                        className={`flex-1 py-4 px-6 font-semibold transition-colors ${activeTab === 'pondok' ? 'bg-white text-green-600' : 'text-white/80 hover:text-white'
                            }`}
                    >
                        <i className="fas fa-mosque mr-2"></i>Izin Pondok
                    </button>
                </div>

                <div className="p-6">
                    {activeTab === 'sekolah' ? (
                        <div className="grid lg:grid-cols-5 gap-6">
                            {/* Left - Form */}
                            <div className="lg:col-span-3 space-y-6">
                                {/* Kategori */}
                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori Izin</label>
                                    <div className="flex gap-3">
                                        <button
                                            onClick={() => handleKategoriChange('sakit')}
                                            className={`flex-1 py-3 rounded-xl font-semibold transition-colors ${kategori === 'sakit' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                                }`}
                                        >
                                            <i className="fas fa-thermometer-half mr-2"></i>Sakit
                                        </button>
                                        <button
                                            onClick={() => handleKategoriChange('izin_pulang')}
                                            className={`flex-1 py-3 rounded-xl font-semibold transition-colors ${kategori === 'izin_pulang' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                                }`}
                                        >
                                            <i className="fas fa-home mr-2"></i>Izin Pulang
                                        </button>
                                    </div>
                                </div>

                                {/* Santri List */}
                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-2">
                                        Pilih Santri <span className="text-gray-400 font-normal">(Maks 5)</span>
                                    </label>
                                    <div className="max-h-64 overflow-y-auto border border-gray-200 rounded-xl bg-gray-50">
                                        {loading ? (
                                            <div className="text-center text-gray-400 py-8">
                                                <i className="fas fa-spinner fa-spin mr-2"></i>Memuat data...
                                            </div>
                                        ) : santriList.length === 0 ? (
                                            <div className="text-center text-gray-400 py-8">
                                                <i className="fas fa-inbox mr-2"></i>
                                                Tidak ada santri dengan status {kategori === 'sakit' ? 'Sakit' : 'Izin Pulang'} dalam 7 hari terakhir
                                            </div>
                                        ) : (
                                            santriList.map((s) => {
                                                const isSelected = selectedSantri.find(x => x.id === s.siswa_id);
                                                return (
                                                    <div
                                                        key={s.aktivitas_id}
                                                        onClick={() => handleSelectSantri(s)}
                                                        className={`flex items-start p-4 border-b border-gray-200 last:border-0 cursor-pointer transition-colors ${isSelected ? 'bg-green-100' : 'hover:bg-green-50'
                                                            }`}
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            checked={!!isSelected}
                                                            onChange={() => { }}
                                                            className="w-5 h-5 mt-1 mr-3 accent-green-500"
                                                        />
                                                        <div className="flex-1">
                                                            <div className="flex justify-between items-center">
                                                                <div>
                                                                    <span className="font-bold text-gray-800">{s.nama_lengkap}</span>
                                                                    <span className="text-gray-400 text-sm ml-2">{s.kelas || '-'}</span>
                                                                </div>
                                                                <small className="text-gray-400">
                                                                    {s.tanggal ? new Date(s.tanggal).toLocaleDateString('id-ID') : '-'}
                                                                </small>
                                                            </div>
                                                            <small className="text-gray-500">{s.judul || s.keterangan || '-'}</small>
                                                        </div>
                                                    </div>
                                                );
                                            })
                                        )}
                                    </div>
                                    <div className="mt-2">
                                        <span className="px-3 py-1 bg-green-100 text-green-600 text-sm font-semibold rounded-full">
                                            {selectedSantri.length}/5 santri dipilih
                                        </span>
                                    </div>
                                </div>

                                {/* Form Fields */}
                                <div className="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-semibold text-gray-700 mb-1">
                                            Tujuan Guru <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={tujuanGuru}
                                            onChange={(e) => setTujuanGuru(e.target.value)}
                                            placeholder="Nama guru..."
                                            className="w-full px-4 py-2 border border-gray-200 rounded-lg"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-semibold text-gray-700 mb-1">
                                            Kelas <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={kelas}
                                            onChange={(e) => setKelas(e.target.value)}
                                            placeholder="Contoh: VII, VIII, IX"
                                            className="w-full px-4 py-2 border border-gray-200 rounded-lg"
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Right - Preview */}
                            <div className="lg:col-span-2">
                                <label className="block text-xs font-bold text-gray-500 uppercase mb-2">Preview Surat</label>
                                <pre className="bg-slate-800 text-slate-300 rounded-xl p-4 text-xs font-mono whitespace-pre overflow-auto max-h-96">
                                    {generatePreview()}
                                </pre>

                                <div className="mt-4 p-3 bg-gray-50 rounded-xl flex items-center gap-2">
                                    <span className="w-3 h-3 rounded-full bg-gray-400"></span>
                                    <span className="text-sm text-gray-600">Status Printer: <strong>Queue Mode</strong></span>
                                </div>

                                <button
                                    onClick={handleCetak}
                                    disabled={!canPrint || printing}
                                    className="w-full mt-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed transition-all"
                                >
                                    {printing ? (
                                        <><i className="fas fa-spinner fa-spin mr-2"></i>Memproses...</>
                                    ) : (
                                        <><i className="fas fa-print mr-2"></i>CETAK SURAT</>
                                    )}
                                </button>
                            </div>
                        </div>
                    ) : (
                        <div className="text-center py-16">
                            <i className="fas fa-tools fa-4x text-gray-300 mb-4"></i>
                            <h4 className="text-xl font-semibold text-gray-500 mb-2">Coming Soon</h4>
                            <p className="text-gray-400">
                                Fitur Izin Pondok sedang dalam pengembangan.<br />
                                Silakan gunakan Izin Sekolah untuk saat ini.
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
