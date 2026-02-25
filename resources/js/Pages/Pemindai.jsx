import React, { useEffect, useState, useRef, useCallback } from 'react';
import Modal from '../Components/Modal';

export default function Pemindai() {
    const [isScanning, setIsScanning] = useState(false);
    const [kodeInput, setKodeInput] = useState('');
    const [recentScans, setRecentScans] = useState([]);
    const [result, setResult] = useState(null); // { success, name, message }
    const [detailModal, setDetailModal] = useState(null); // data izin
    const [errorModal, setErrorModal] = useState(null); // error message
    const [isLoading, setIsLoading] = useState(false);
    const scannerRef = useRef(null);
    const readerRef = useRef(null);

    useEffect(() => {
        document.title = 'Scan QR - Aktivitas Santri';
        return () => {
            stopScanner();
        };
    }, []);

    // Start scanner
    const startScanner = async () => {
        setIsScanning(true);

        // Dynamically import html5-qrcode
        try {
            const { Html5Qrcode } = await import('html5-qrcode');
            scannerRef.current = new Html5Qrcode("qr-reader");

            await scannerRef.current.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                () => { } // onScanFailure - ignore
            );
        } catch (err) {
            console.error('Camera error:', err);
            alert('Tidak dapat mengakses kamera: ' + err);
            stopScanner();
        }
    };

    // Stop scanner
    const stopScanner = useCallback(() => {
        if (scannerRef.current) {
            scannerRef.current.stop().catch(() => { });
            scannerRef.current = null;
        }
        setIsScanning(false);
    }, []);

    // On scan success
    const onScanSuccess = (decodedText) => {
        if (scannerRef.current) {
            scannerRef.current.pause();
        }
        searchByKode(decodedText.trim());
    };

    // Resume scanner
    const resumeScanner = () => {
        if (scannerRef.current && isScanning) {
            try {
                scannerRef.current.resume();
            } catch (e) { }
        }
    };

    // Search by kode
    const searchByKode = async (kode) => {
        if (!kode) {
            alert('Masukkan kode konfirmasi terlebih dahulu');
            return;
        }

        setIsLoading(true);
        try {
            const formData = new FormData();
            formData.append('kode', kode);

            const response = await fetch('/api/public/konfirmasi/search', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });

            const result = await response.json();

            if (result.status === 'success') {
                setDetailModal(result.data);
            } else {
                setErrorModal(result.message || 'Kode tidak ditemukan');
            }
        } catch (error) {
            setErrorModal('Terjadi kesalahan: ' + error.message);
        } finally {
            setIsLoading(false);
            setTimeout(resumeScanner, 1000);
        }
    };

    // Handle manual search
    const handleSearch = () => {
        searchByKode(kodeInput.trim());
    };

    // Handle key press
    const handleKeyPress = (e) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    };

    // Konfirmasi kembali
    const handleKonfirmasi = async () => {
        if (!detailModal) return;

        setIsLoading(true);
        try {
            const formData = new FormData();
            formData.append('id', detailModal.id);

            const response = await fetch('/api/public/konfirmasi/direct', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });

            const result = await response.json();

            if (result.status === 'success') {
                showResult(true, detailModal.nama_lengkap, result.message);
                addRecentScan(detailModal.nama_lengkap, true, result.data?.terlambat ? 'Terlambat' : 'Tepat Waktu');
                setKodeInput('');
            } else {
                showResult(false, detailModal.nama_lengkap, result.message);
                addRecentScan(detailModal.nama_lengkap, false, result.message);
            }
        } catch (error) {
            showResult(false, 'Error', 'Terjadi kesalahan: ' + error.message);
        } finally {
            setIsLoading(false);
            setDetailModal(null);
        }
    };

    // Show result
    const showResult = (success, name, message) => {
        setResult({ success, name, message });
        setTimeout(() => setResult(null), 3000);
    };

    // Add to recent scans
    const addRecentScan = (name, success, message) => {
        const newScan = {
            name,
            success,
            message,
            time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
        };
        setRecentScans(prev => [newScan, ...prev.slice(0, 4)]);
    };

    // Get kategori badge class
    const getKategoriBadge = (kategori) => {
        if (kategori === 'sakit') return 'bg-red-100 text-red-500';
        if (kategori === 'izin_keluar') return 'bg-amber-100 text-amber-500';
        if (kategori === 'izin_pulang') return 'bg-orange-100 text-orange-500';
        if (kategori === 'sambangan') return 'bg-emerald-100 text-emerald-500';
        if (kategori === 'pelanggaran') return 'bg-pink-100 text-pink-500';
        return 'bg-blue-100 text-blue-500';
    };

    return (
        <>
            <div className="grid md:grid-cols-2 gap-6">
                {/* Left Card - Scanner & Input */}
                <div className="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    {/* Scanner Area */}
                    {isScanning ? (
                        <div>
                            <div className="bg-gray-50 rounded-xl p-4 text-center mb-3">
                                <i className="fas fa-camera text-2xl text-blue-500 mb-2"></i>
                                <p className="text-sm text-gray-500 mb-0">Arahkan kamera ke QR Code slip izin santri</p>
                            </div>

                            <div id="qr-reader" ref={readerRef} className="mb-3 rounded-xl overflow-hidden"></div>

                            <button
                                onClick={stopScanner}
                                className="w-full py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors"
                            >
                                <i className="fas fa-stop mr-2"></i>Tutup Kamera
                            </button>
                        </div>
                    ) : (
                        <div>
                            {/* Start Button Area */}
                            <div className="bg-gray-50 rounded-xl p-6 text-center mb-4">
                                <i className="fas fa-qrcode text-5xl text-blue-500 mb-3"></i>
                                <h6 className="font-bold mb-1 text-gray-800">Konfirmasi Kembali</h6>
                                <p className="text-sm text-gray-500 mb-0">Scan QR Code atau masukkan kode unik untuk konfirmasi santri sudah kembali</p>
                            </div>

                            <button
                                onClick={startScanner}
                                className="w-full py-3 bg-blue-500 text-white rounded-xl font-bold text-lg hover:bg-blue-600 transition-colors"
                            >
                                <i className="fas fa-camera mr-2"></i>Mulai Scan
                            </button>
                        </div>
                    )}

                    {/* Divider */}
                    <div className="flex items-center my-6">
                        <div className="flex-1 border-b border-gray-200"></div>
                        <span className="px-4 text-sm text-gray-400">atau masukkan kode</span>
                        <div className="flex-1 border-b border-gray-200"></div>
                    </div>

                    {/* Manual Input */}
                    <div className="mb-3">
                        <input
                            type="text"
                            value={kodeInput}
                            onChange={(e) => setKodeInput(e.target.value.toUpperCase())}
                            onKeyPress={handleKeyPress}
                            placeholder="XXXXXX"
                            maxLength={10}
                            className="w-full text-center text-xl font-bold tracking-widest uppercase py-4 px-4 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 focus:outline-none transition-colors"
                        />
                    </div>
                    <button
                        onClick={handleSearch}
                        disabled={isLoading}
                        className="w-full py-3 border-2 border-blue-500 text-blue-500 rounded-xl font-semibold hover:bg-blue-50 transition-colors disabled:opacity-50"
                    >
                        {isLoading ? (
                            <><i className="fas fa-spinner fa-spin mr-2"></i>Mencari...</>
                        ) : (
                            <><i className="fas fa-search mr-2"></i>Cari</>
                        )}
                    </button>

                    {/* Result Area */}
                    {result && (
                        <div className={`mt-4 rounded-xl p-6 text-center text-white ${result.success ? 'bg-gradient-to-br from-green-500 to-green-600' : 'bg-gradient-to-br from-red-500 to-red-600'}`}>
                            <i className={`fas fa-${result.success ? 'check-circle' : 'times-circle'} text-5xl mb-2`}></i>
                            <h5 className="font-bold mb-1">{result.name}</h5>
                            <p className="mb-0">{result.message}</p>
                        </div>
                    )}
                </div>

                {/* Right Card - Recent Confirmations */}
                <div className="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h6 className="font-bold text-gray-700 mb-4 text-sm flex items-center gap-2">
                        <i className="fas fa-history text-blue-500"></i>Konfirmasi Terakhir
                    </h6>
                    <div className="space-y-2">
                        {recentScans.length === 0 ? (
                            <div className="text-center text-gray-400 text-sm py-12">
                                <i className="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                Belum ada konfirmasi
                            </div>
                        ) : (
                            recentScans.map((scan, idx) => (
                                <div key={idx} className="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                    <div>
                                        <i className={`fas fa-${scan.success ? 'check text-green-500' : 'times text-red-500'} mr-2`}></i>
                                        <strong className="text-gray-800 text-sm">{scan.name}</strong>
                                        <small className="text-gray-500 block ml-6">{scan.message}</small>
                                    </div>
                                    <small className="text-gray-400">{scan.time}</small>
                                </div>
                            ))
                        )}
                    </div>

                    {/* Detail Modal */}
                    <Modal isOpen={!!detailModal} onClose={() => setDetailModal(null)} className="max-w-sm">
                        <div className="relative bg-white rounded-2xl shadow-xl w-full overflow-hidden">
                            {/* Header */}
                            <div className="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-6 py-4 flex items-center justify-between">
                                <h5 className="font-bold flex items-center gap-2">
                                    <i className="fas fa-clipboard-check"></i>Detail Izin
                                </h5>
                                <button onClick={() => setDetailModal(null)} className="text-white/80 hover:text-white">
                                    <i className="fas fa-times"></i>
                                </button>
                            </div>

                            {/* Body */}
                            <div className="p-6">
                                {/* Avatar & Name */}
                                <div className="text-center mb-6">
                                    <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-400 rounded-full flex items-center justify-center mx-auto text-white text-2xl font-bold">
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
                                        <span className="text-gray-500 text-sm">Keterangan</span>
                                        <span className="font-semibold text-gray-800 text-right max-w-[180px] break-words">{detailModal.keterangan || '-'}</span>
                                    </div>
                                    <div className="flex justify-between py-2 border-b border-gray-100">
                                        <span className="text-gray-500 text-sm">Tanggal Izin</span>
                                        <span className="font-semibold text-gray-800">{detailModal.tanggal || '-'}</span>
                                    </div>
                                    <div className="flex justify-between py-2">
                                        <span className="text-gray-500 text-sm">Batas Waktu</span>
                                        <span className="font-semibold text-gray-800">{detailModal.batas_waktu || '-'}</span>
                                    </div>
                                </div>
                            </div>

                            {/* Footer */}
                            <div className="px-6 pb-6 flex gap-3">
                                <button
                                    onClick={() => setDetailModal(null)}
                                    className="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    onClick={handleKonfirmasi}
                                    disabled={isLoading}
                                    className="flex-1 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors disabled:opacity-50"
                                >
                                    {isLoading ? (
                                        <><i className="fas fa-spinner fa-spin mr-1"></i>Memproses...</>
                                    ) : (
                                        <><i className="fas fa-check mr-1"></i>Konfirmasi Kembali</>
                                    )}
                                </button>
                            </div>
                        </div>
                    </Modal>

                    {/* Error Modal */}
                    <Modal isOpen={!!errorModal} onClose={() => setErrorModal(null)} className="max-w-xs">
                        <div className="relative bg-white rounded-2xl shadow-xl w-full overflow-hidden p-6 text-center">
                            <div className="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i className="fas fa-times text-white text-2xl"></i>
                            </div>
                            <h5 className="font-bold mb-2 text-gray-800">Tidak Ditemukan</h5>
                            <p className="text-gray-500 mb-4">{errorModal}</p>
                            <button
                                onClick={() => setErrorModal(null)}
                                className="w-full py-3 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-colors"
                            >
                                Tutup
                            </button>
                        </div>
                    </Modal>
                </div>
            </div>
        </>
    );
}
