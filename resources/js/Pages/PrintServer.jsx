import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import StandaloneLayout from '@/Layouts/StandaloneLayout';
import LoadingSpinner from '../Components/LoadingSpinner';

export default function PrintServer() {
    const [serverRunning, setServerRunning] = useState(false);
    const [status, setStatus] = useState('inactive');
    const [statusText, setStatusText] = useState('Menunggu koneksi...');
    const [stats, setStats] = useState({ pending: 0, processing: 0, completed: 0, failed: 0 });
    const [logs, setLogs] = useState([{ time: new Date().toLocaleTimeString('id-ID'), message: '[System] Print Server siap. Klik "Mulai Server" untuk memulai.', type: 'info' }]);
    const [qzLoaded, setQzLoaded] = useState(false);

    const logsEndRef = useRef(null);
    const pollIntervalRef = useRef(null);

    useEffect(() => {
        document.title = 'Print Server - Aktivitas Santri';
        // Load QZ Tray script
        if (!window.qz) {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js';
            script.async = true;
            script.onload = () => {
                setQzLoaded(true);
                setupQzSecurity();
            };
            document.body.appendChild(script);
        } else {
            setQzLoaded(true);
            setupQzSecurity();
        }

        updateStats();

        return () => {
            if (pollIntervalRef.current) clearInterval(pollIntervalRef.current);
        };
    }, []);

    useEffect(() => {
        scrollToBottom();
    }, [logs]);

    const scrollToBottom = () => {
        logsEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    const setupQzSecurity = () => {
        if (!window.qz) return;

        window.qz.security.setCertificatePromise((resolve, reject) => {
            fetch('/api/qz/certificate')
                .then(res => res.text())
                .then(resolve)
                .catch(reject);
        });

        window.qz.security.setSignaturePromise((toSign) => {
            return (resolve, reject) => {
                axios.post('/api/qz/sign', { request: toSign }, {
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    transformRequest: [(data) => `request=${encodeURIComponent(data.request)}`]
                })
                    .then(res => resolve(res.data))
                    .catch(reject);
            };
        });
    };

    const addLog = (message, type = 'info') => {
        setLogs(prev => [...prev, {
            time: new Date().toLocaleTimeString('id-ID'),
            message,
            type
        }]);
    };

    const updateStats = async () => {
        try {
            const res = await axios.get('/api/print-queue/stats');
            if (res.data.success && res.data.stats) {
                setStats({
                    pending: res.data.stats.pending || 0,
                    processing: res.data.stats.processing || 0,
                    completed: res.data.stats.completed_today || 0,
                    failed: res.data.stats.failed_today || 0
                });
            }
        } catch (e) {
            console.error('Stats error:', e);
        }
    };

    const startServer = async () => {
        addLog('Menginisialisasi QZ Tray...', 'info');
        try {
            if (window.qz && !window.qz.websocket.isActive()) {
                await window.qz.websocket.connect();
            }
            addLog('✅ QZ Tray terhubung', 'success');
            setServerRunning(true);
            setStatus('active');
            setStatusText('Server Aktif - Menunggu antrian...');
            addLog('🚀 Print Server dimulai. Polling setiap 3 detik...', 'success');

            pollIntervalRef.current = setInterval(pollQueue, 3000);
            pollQueue();
        } catch (e) {
            addLog('⚠️ QZ Tray tidak tersedia: ' + e.message, 'warning');
            setServerRunning(true);
            setStatus('active');
            setStatusText('Server Aktif (Queue Mode)');
            pollIntervalRef.current = setInterval(pollQueue, 3000);
            pollQueue();
        }
    };

    const stopServer = () => {
        setServerRunning(false);
        if (pollIntervalRef.current) {
            clearInterval(pollIntervalRef.current);
            pollIntervalRef.current = null;
        }
        setStatus('inactive');
        setStatusText('Server Dihentikan');
        addLog('⏹️ Print Server dihentikan', 'warning');
    };

    const pollQueue = async () => {
        try {
            const res = await axios.get('/api/print-queue/pending');
            updateStats();
            if (res.data.success && res.data.jobs && res.data.jobs.length > 0) {
                for (const job of res.data.jobs) {
                    await processJob(job);
                }
            }
        } catch (e) {
            console.error('Poll error:', e);
        }
    };

    const processJob = async (job) => {
        addLog(`📄 Memproses job #${job.id}: ${job.job_type}`, 'info');
        setStatus('printing');
        setStatusText('Mencetak...');

        try {
            await axios.post(`/api/print-queue/${job.id}/processing`);

            let printData = [];
            if (job.job_type === 'slip_konfirmasi') {
                printData = generateSlipKonfirmasi(job.job_data);
            } else if (job.job_type === 'surat_izin') {
                printData = generateSuratIzin(job.job_data);
            }

            if (window.qz && window.qz.websocket.isActive()) {
                const printer = await window.qz.printers.getDefault();
                const config = window.qz.configs.create(printer, { encoding: 'UTF-8' });
                await window.qz.print(config, printData);
                addLog(`✅ Job #${job.id} tercetak ke ${printer}`, 'success');
            } else {
                addLog(`⚠️ QZ tidak aktif, job #${job.id} ditandai selesai`, 'warning');
            }

            await axios.post(`/api/print-queue/${job.id}/complete`);
            addLog(`✅ Job #${job.id} berhasil`, 'success');
        } catch (e) {
            addLog(`❌ Job #${job.id} gagal: ${e.message}`, 'error');
            await axios.post(`/api/print-queue/${job.id}/fail`, null, {
                params: { error: e.message }
            });
        }

        setStatus('active');
        setStatusText('Server Aktif - Menunggu antrian...');
        updateStats();
    };

    const generateSlipKonfirmasi = (data) => {
        const ESC = '\x1B', GS = '\x1D';
        const kode = data.kode_konfirmasi || '000000';
        const qrData = kode;
        const dataLen = qrData.length + 3;
        const pL = dataLen % 256;
        const pH = Math.floor(dataLen / 256);

        let c = [];
        c.push({ type: 'raw', format: 'plain', data: ESC + '@' });
        c.push({ type: 'raw', format: 'plain', data: ESC + 'a\x01' });
        c.push({ type: 'raw', format: 'plain', data: ESC + 'E\x01' + GS + '!\x10' });
        c.push({ type: 'raw', format: 'plain', data: (data.nama_santri || '-').substring(0, 20) + '\n' });
        c.push({ type: 'raw', format: 'plain', data: GS + '!\x00' + ESC + 'E\x00' });
        c.push({ type: 'raw', format: 'plain', data: 'Kelas ' + (data.kelas || '-') + '\n' });
        c.push({ type: 'raw', format: 'plain', data: '--------------------------------\n' });
        c.push({ type: 'raw', format: 'plain', data: ESC + 'a\x00' });
        c.push({ type: 'raw', format: 'plain', data: 'Keperluan: ' + (data.judul || '-').substring(0, 20) + '\n' });
        let batas = data.batas_waktu ? new Date(data.batas_waktu).toLocaleString('id-ID', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' }) : '-';
        c.push({ type: 'raw', format: 'plain', data: 'Batas    : ' + batas + '\n' });
        c.push({ type: 'raw', format: 'plain', data: '--------------------------------\n' });
        c.push({ type: 'raw', format: 'plain', data: ESC + 'a\x01' });

        c.push({ type: 'raw', format: 'plain', data: GS + '(k\x04\x00\x31\x41\x32\x00' });
        c.push({ type: 'raw', format: 'plain', data: GS + '(k\x03\x00\x31\x43\x04' });
        c.push({ type: 'raw', format: 'plain', data: GS + '(k\x03\x00\x31\x45\x30' });
        c.push({ type: 'raw', format: 'plain', data: GS + '(k' + String.fromCharCode(pL, pH) + '\x31\x50\x30' + qrData });
        c.push({ type: 'raw', format: 'plain', data: GS + '(k\x03\x00\x31\x51\x30' });

        c.push({ type: 'raw', format: 'plain', data: '\n' });
        c.push({ type: 'raw', format: 'plain', data: ESC + 'E\x01' + GS + '!\x11' });
        c.push({ type: 'raw', format: 'plain', data: kode + '\n' });
        c.push({ type: 'raw', format: 'plain', data: GS + '!\x00' + ESC + 'E\x00' });
        c.push({ type: 'raw', format: 'plain', data: 'Kode Konfirmasi\n--------------------------------\n' });
        c.push({ type: 'raw', format: 'plain', data: 'Scan QR/input kode di:\n/konfirmasi-kembali\n\n\n\n' });
        c.push({ type: 'raw', format: 'plain', data: GS + 'V\x00' });
        return c;
    };

    const generateSuratIzin = (data) => {
        return [{ type: 'raw', format: 'plain', data: 'Surat Izin: ' + (data.nomor_surat || '-') + '\n\n\n' }];
    };

    return (
        <StandaloneLayout>

            <div className="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center p-6">
                <div className="bg-white rounded-[40px] shadow-2xl p-10 w-full max-w-xl">
                    <div className="text-center mb-10">
                        <div className="w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-400 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/20">
                            <i className="fas fa-print text-3xl text-white"></i>
                        </div>
                        <h1 className="text-3xl font-extrabold text-slate-800 mb-2">Print Server</h1>
                        <p className="text-slate-400 font-medium">Otomatis mencetak slip dari antrian</p>
                    </div>

                    <div className="flex items-center justify-center gap-3 p-5 rounded-3xl bg-emerald-50 mb-8 border border-emerald-100">
                        {status === 'active' || status === 'printing' ? (
                            <LoadingSpinner size="small" />
                        ) : (
                            <div className="w-3 h-3 rounded-full bg-red-500"></div>
                        )}
                        <span className="font-bold text-emerald-800">{statusText}</span>
                    </div>

                    <div className="grid grid-cols-4 gap-4 mb-8">
                        {[
                            { label: 'Antrian', val: stats.pending, color: 'emerald' },
                            { label: 'Proses', val: stats.processing, color: 'blue' },
                            { label: 'Selesai', val: stats.completed, color: 'indigo' },
                            { label: 'Gagal', val: stats.failed, color: 'red' }
                        ].map(s => (
                            <div key={s.label} className="bg-slate-50 p-4 rounded-2xl text-center border border-slate-100">
                                <div className={`text-2xl font-black text-${s.color}-600`}>{s.val}</div>
                                <div className="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1">{s.label}</div>
                            </div>
                        ))}
                    </div>

                    <div className="flex gap-4 mb-8">
                        <button
                            disabled={serverRunning}
                            onClick={startServer}
                            className="flex-1 py-4 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-2xl transition-all shadow-lg shadow-emerald-500/20"
                        >
                            <i className="fas fa-play mr-2"></i> Mulai Server
                        </button>
                        <button
                            disabled={!serverRunning}
                            onClick={stopServer}
                            className="px-8 py-4 bg-red-600 hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-2xl transition-all shadow-lg shadow-red-500/20"
                        >
                            <i className="fas fa-stop mr-2"></i> Stop
                        </button>
                    </div>

                    <div className="bg-slate-900 rounded-3xl p-5 h-64 overflow-y-auto font-mono text-xs text-slate-400 shadow-inner">
                        {logs.map((log, i) => (
                            <div key={i} className="mb-2 break-words">
                                <span className="text-slate-600">[{log.time}]</span>{' '}
                                <span className={
                                    log.type === 'success' ? 'text-emerald-400' :
                                        log.type === 'error' ? 'text-red-400' :
                                            log.type === 'warning' ? 'text-amber-400' : 'text-blue-400'
                                }>
                                    {log.message}
                                </span>
                            </div>
                        ))}
                        <div ref={logsEndRef} />
                    </div>

                    <div className="mt-8 text-center">
                        <p className="text-slate-300 text-sm flex items-center justify-center">
                            <i className="fas fa-info-circle mr-2"></i>
                            Biarkan halaman ini tetap terbuka
                        </p>
                    </div>
                </div>
            </div>
        </StandaloneLayout>
    );
}
