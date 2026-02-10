import React, { useState, useEffect } from 'react';
import axios from 'axios';

export default function CetakKartu() {
    const [siswaList, setSiswaList] = useState([]);
    const [schoolName, setSchoolName] = useState('');
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        document.title = 'Cetak Kartu Santri - Aktivitas Santri';
        const params = new URLSearchParams(window.location.search);
        const ids = params.get('ids');

        if (!ids) {
            alert('Tidak ada siswa yang dipilih');
            window.close();
            return;
        }

        fetchData(ids);
    }, []);

    const fetchData = async (ids) => {
        setIsLoading(true);
        try {
            const response = await axios.get(`/cetak-kartu?ids=${ids}`);
            setSiswaList(response.data.siswaList);
            setSchoolName(response.data.schoolName);
        } catch (error) {
            console.error('Error fetching data:', error);
            alert('Gagal mengambil data siswa');
        } finally {
            setIsLoading(false);
        }
    };

    if (isLoading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-50">
                <div className="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
            </div>
        );
    }

    return (
        <div className="p-5 font-['Poppins',_sans-serif] bg-slate-100 min-h-screen print:bg-white print:p-0">

            <div className="text-center mb-5 p-4 bg-white rounded-xl shadow-sm print:hidden flex items-center justify-center gap-4">
                <h4 className="text-slate-800 font-bold m-0 flex items-center">
                    <i className="fas fa-id-card mr-2 text-blue-500"></i>
                    Cetak {siswaList.length} Kartu Santri
                </h4>
                <button
                    onClick={() => window.print()}
                    className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold transition-all flex items-center shadow-lg shadow-blue-500/20"
                >
                    <i className="fas fa-print mr-2"></i> Cetak Sekarang
                </button>
                <button
                    onClick={() => window.close()}
                    className="bg-slate-500 hover:bg-slate-600 text-white px-6 py-2 rounded-lg font-bold transition-all flex items-center"
                >
                    <i className="fas fa-times mr-2"></i> Tutup
                </button>
            </div>

            <div className="grid grid-cols-[repeat(auto-fill,340px)] gap-5 justify-center print:grid-cols-2 print:gap-[10mm] print:m-[10mm]">
                <style dangerouslySetInnerHTML={{
                    __html: `
                    @media print {
                        body { background: white !important; }
                        @page { size: A4; margin: 10mm; }
                    }
                ` }} />

                {siswaList.map(siswa => {
                    const nisn = siswa.nisn || '------';
                    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(nisn)}`;

                    return (
                        <div key={siswa.id} className="w-[340px] h-[215px] bg-gradient-to-br from-[#1e3a5f] via-[#3b82f6] to-[#60a5fa] rounded-2xl relative overflow-hidden shadow-2xl shadow-blue-900/30 text-white print:shadow-none print:break-inside-avoid">
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
                                <div className="flex-1 flex flex-col justify-center">
                                    <div className="text-[13px] font-extrabold leading-tight mb-1">
                                        {siswa.nama_lengkap}
                                    </div>
                                    <div className="text-lg font-bold font-mono tracking-[2px] mb-1.5 text-amber-400">
                                        {nisn.substring(0, 6)}
                                    </div>
                                    <div className="text-[10px] opacity-90">
                                        <span className="bg-white/20 px-2.5 py-0.5 rounded-full font-bold">
                                            Kelas {siswa.kelas}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Card Footer */}
                            <div className="absolute bottom-2.5 left-4 right-4 flex justify-between items-center text-[7px] opacity-70 z-10">
                                <div className="w-9 h-6.5 bg-gradient-to-br from-amber-400 to-amber-700 rounded relative">
                                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-6 h-4.5 border border-black/20 rounded-sm"></div>
                                </div>
                                <div className="font-bold tracking-tight">Scan QR untuk absensi</div>
                            </div>

                            {/* Background Decoration */}
                            <div className="absolute -top-12 -right-12 w-36 h-36 bg-white/10 rounded-full z-0"></div>
                            <div className="absolute -bottom-10 -left-10 w-24 h-24 bg-white/5 rounded-full z-0"></div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
