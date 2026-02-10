import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { useParams } from 'react-router-dom';
import html2canvas from 'html2canvas';

export default function KartuQr() {
    const { id } = useParams();

    const [siswa, setSiswa] = useState(null);
    const [schoolName, setSchoolName] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [isDownloading, setIsDownloading] = useState(false);

    const cardRef = useRef(null);

    useEffect(() => {
        if (id) fetchData();
    }, [id]);

    const fetchData = async () => {
        setIsLoading(true);
        try {
            const response = await axios.get(`/kartu-qr/${id}`);
            setSiswa(response.data.siswa);
            setSchoolName(response.data.schoolName);
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            setIsLoading(false);
        }
    };

    const downloadCard = async () => {
        if (!cardRef.current) return;
        setIsDownloading(true);
        try {
            // Wait slightly for any rendering adjustments
            await new Promise(r => setTimeout(r, 150));

            const canvas = await html2canvas(cardRef.current, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                logging: false
            });

            const link = document.createElement('a');
            const sanitizedName = siswa.nama_lengkap.replace(/[^a-zA-Z0-9]/g, '_');
            link.download = `Kartu_${siswa.nisn || siswa.id}_${sanitizedName}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (error) {
            console.error('Download error:', error);
            alert('Gagal mengunduh kartu. Silakan gunakan tombol cetak.');
        } finally {
            setIsDownloading(false);
        }
    };

    if (isLoading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-50">
                <div className="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
            </div>
        );
    }

    if (!siswa) {
        return (
            <div className="min-h-screen flex flex-col items-center justify-center bg-slate-50 p-6">
                <i className="fas fa-exclamation-triangle text-6xl text-amber-500 mb-4"></i>
                <h1 className="text-2xl font-bold text-slate-800">Data Tidak Ditemukan</h1>
                <p className="text-slate-500 mt-2 mb-8">Maaf, data santri tidak ditemukan atau sudah dihapus.</p>
                <a href="/admin/santri" className="bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/20">
                    Kembali ke Daftar
                </a>
            </div>
        );
    }

    const nisn = siswa.nisn || '------';
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(nisn)}`;

    return (
        <div className="p-6 md:p-10 bg-slate-100 min-h-screen print:bg-white print:p-0">

            <div className="mb-8 print:hidden">
                <a href="/admin/santri" className="bg-white border text-slate-600 px-6 py-2 rounded-xl font-bold inline-flex items-center hover:bg-slate-50 transition-colors">
                    <i className="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div className="flex flex-col items-center justify-center min-h-[60vh] print:min-h-0 print:justify-start">
                <div
                    ref={cardRef}
                    className="w-[340px] h-[195px] bg-gradient-to-br from-[#1e3a5f] via-[#3b82f6] to-[#60a5fa] rounded-2xl relative overflow-hidden shadow-2xl shadow-blue-900/40 text-white print:shadow-none print:break-inside-avoid"
                >
                    {/* Background Decoration */}
                    <div className="absolute -top-12 -right-12 w-36 h-36 bg-white/10 rounded-full z-0"></div>
                    <div className="absolute -bottom-20 -left-20 w-48 h-48 bg-white/5 rounded-full z-0"></div>

                    {/* Card Header */}
                    <div className="p-3 pl-4 pr-4 flex justify-between items-start relative z-10">
                        <div className="flex-1">
                            <div className="text-[9px] font-bold uppercase tracking-wider opacity-90 leading-tight">
                                {schoolName}
                            </div>
                            <div className="text-[11px] font-extrabold uppercase tracking-widest mt-0.5 text-amber-400">
                                Kartu Santri
                            </div>
                        </div>
                        <div className="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center p-1">
                            <img src="/logo-pondok.png" alt="Logo" className="w-7 h-7 object-contain" />
                        </div>
                    </div>

                    {/* Card Body */}
                    <div className="px-4 flex gap-3.5 relative z-10">
                        <div className="bg-white p-1.5 rounded-lg shadow-xl shadow-black/20">
                            <img src={qrUrl} alt="QR Code" className="w-[90px] h-[90px] block" />
                        </div>
                        <div className="flex-1 flex flex-col justify-between h-[102px]">
                            <div className="flex flex-col">
                                <div className="text-[13px] font-extrabold leading-tight mb-1 text-shadow-sm">
                                    {siswa.nama_lengkap}
                                </div>
                                <div className="text-[16px] font-bold font-mono tracking-[2px] mb-1.5 text-amber-400">
                                    {nisn}
                                </div>
                                <div className="text-[10px] opacity-90">
                                    <span className="bg-white/20 px-2.5 py-0.5 rounded-full font-bold">
                                        Kelas {siswa.kelas}
                                    </span>
                                </div>
                            </div>
                            <div className="text-[8px] opacity-70 text-right uppercase tracking-tighter">
                                Scan QR untuk absensi
                            </div>
                        </div>
                    </div>
                </div>

                <div className="flex gap-4 mt-10 print:hidden">
                    <button
                        onClick={() => window.print()}
                        className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-bold transition-all flex items-center shadow-lg shadow-blue-500/20"
                    >
                        <i className="fas fa-print mr-2"></i> Cetak Kartu
                    </button>
                    <button
                        disabled={isDownloading}
                        onClick={downloadCard}
                        className="bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white px-8 py-3 rounded-2xl font-bold transition-all flex items-center shadow-lg shadow-emerald-500/20"
                    >
                        {isDownloading ? (
                            <><span className="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> Menyiapkan...</>
                        ) : (
                            <><i className="fas fa-download mr-2"></i> Download Kartu</>
                        )}
                    </button>
                </div>

                <div className="mt-8 text-center text-slate-400 text-xs print:hidden">
                    <i className="fas fa-info-circle mr-1"></i> Ukuran kartu sesuai standar kartu ATM (85.6 × 54 mm)
                </div>
            </div>

            <style dangerouslySetInnerHTML={{
                __html: `
                @media print {
                    body { background: white !important; }
                    .main-layout-container { margin: 0 !important; padding: 0 !important; }
                }
            ` }} />
        </div>
    );
}
