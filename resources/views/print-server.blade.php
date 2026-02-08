<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Print Server - Aktivitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .server-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }
        .status-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        .status-active { background: #10b981; }
        .status-inactive { background: #ef4444; animation: none; }
        .status-printing { background: #f59e0b; }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }
        .log-container {
            background: #1e293b;
            color: #94a3b8;
            border-radius: 12px;
            padding: 16px;
            height: 250px;
            overflow-y: auto;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 0.8rem;
        }
        .log-success { color: #10b981; }
        .log-error { color: #ef4444; }
        .log-info { color: #60a5fa; }
        .log-warning { color: #f59e0b; }
        .stat-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #059669;
        }
        .stat-number.text-danger { color: #ef4444 !important; }
        .brand-icon { 
            width: 60px; 
            height: 60px; 
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        .brand-icon i { font-size: 1.8rem; color: white; }
    </style>
</head>
<body>
    <div class="server-card">
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="fas fa-print"></i>
            </div>
            <h4 class="fw-bold mb-1">Print Server</h4>
            <p class="text-muted small mb-0">Otomatis mencetak slip dari antrian</p>
        </div>

        <div class="d-flex align-items-center justify-content-center mb-4 p-3 rounded" style="background: #f0fdf4;">
            <span class="status-indicator status-inactive" id="statusIndicator"></span>
            <span class="fw-bold" id="statusText">Menunggu koneksi...</span>
        </div>

        <div class="row g-2 mb-4">
            <div class="col-3">
                <div class="stat-box">
                    <div class="stat-number" id="statPending">0</div>
                    <small class="text-muted">Antrian</small>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-box">
                    <div class="stat-number" id="statProcessing">0</div>
                    <small class="text-muted">Proses</small>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-box">
                    <div class="stat-number" id="statCompleted">0</div>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-box">
                    <div class="stat-number text-danger" id="statFailed">0</div>
                    <small class="text-muted">Gagal</small>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-success flex-grow-1 py-2" id="btnStart" onclick="startServer()">
                <i class="fas fa-play me-1"></i> Mulai Server
            </button>
            <button class="btn btn-danger py-2 px-4" id="btnStop" onclick="stopServer()" disabled>
                <i class="fas fa-stop me-1"></i> Stop
            </button>
        </div>

        <div class="log-container" id="logContainer">
            <div class="log-info">[System] Print Server siap. Klik "Mulai Server" untuk memulai.</div>
        </div>

        <div class="mt-3 text-center">
            <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Biarkan halaman ini terbuka</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js"></script>
    <script>
        // QZ Tray Certificate and Signing Callbacks
        qz.security.setCertificatePromise(function(resolve, reject) {
            fetch('/api/qz/certificate')
                .then(res => res.text())
                .then(resolve)
                .catch(reject);
        });

        qz.security.setSignaturePromise(function(toSign) {
            return function(resolve, reject) {
                fetch('/api/qz/sign', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: 'request=' + encodeURIComponent(toSign)
                })
                .then(res => res.text())
                .then(resolve)
                .catch(reject);
            };
        });

        let serverRunning = false;
        let pollInterval = null;
        const POLL_INTERVAL_MS = 3000;

        function addLog(message, type = 'info') {
            const container = document.getElementById('logContainer');
            const time = new Date().toLocaleTimeString('id-ID');
            const div = document.createElement('div');
            div.className = `log-${type}`;
            div.innerHTML = `[${time}] ${message}`;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        function setStatus(status, text) {
            document.getElementById('statusIndicator').className = 'status-indicator status-' + status;
            document.getElementById('statusText').textContent = text;
        }

        async function updateStats() {
            try {
                const res = await fetch('/api/print-queue/stats');
                const data = await res.json();
                if (data.success && data.stats) {
                    document.getElementById('statPending').textContent = data.stats.pending || 0;
                    document.getElementById('statProcessing').textContent = data.stats.processing || 0;
                    document.getElementById('statCompleted').textContent = data.stats.completed_today || 0;
                    document.getElementById('statFailed').textContent = data.stats.failed_today || 0;
                }
            } catch (e) { console.error('Stats error:', e); }
        }

        async function startServer() {
            addLog('Menginisialisasi QZ Tray...', 'info');
            try {
                if (typeof qz !== 'undefined' && !qz.websocket.isActive()) {
                    await qz.websocket.connect();
                }
                addLog('✅ QZ Tray terhubung', 'success');
                serverRunning = true;
                document.getElementById('btnStart').disabled = true;
                document.getElementById('btnStop').disabled = false;
                setStatus('active', 'Server Aktif - Menunggu antrian...');
                addLog('🚀 Print Server dimulai. Polling setiap 3 detik...', 'success');
                pollInterval = setInterval(pollQueue, POLL_INTERVAL_MS);
                pollQueue();
            } catch (e) {
                addLog('⚠️ QZ Tray tidak tersedia: ' + e.message, 'warning');
                serverRunning = true;
                document.getElementById('btnStart').disabled = true;
                document.getElementById('btnStop').disabled = false;
                setStatus('active', 'Server Aktif (Queue Mode)');
                pollInterval = setInterval(pollQueue, POLL_INTERVAL_MS);
                pollQueue();
            }
        }

        function stopServer() {
            serverRunning = false;
            if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
            document.getElementById('btnStart').disabled = false;
            document.getElementById('btnStop').disabled = true;
            setStatus('inactive', 'Server Dihentikan');
            addLog('⏹️ Print Server dihentikan', 'warning');
        }

        async function pollQueue() {
            if (!serverRunning) return;
            try {
                const res = await fetch('/api/print-queue/pending');
                const data = await res.json();
                updateStats();
                if (data.success && data.jobs && data.jobs.length > 0) {
                    for (const job of data.jobs) { await processJob(job); }
                }
            } catch (e) { console.error('Poll error:', e); }
        }

        async function processJob(job) {
            addLog(`📄 Memproses job #${job.id}: ${job.job_type}`, 'info');
            setStatus('printing', 'Mencetak...');
            try {
                await fetch(`/api/print-queue/${job.id}/processing`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                
                let printData = [];
                if (job.job_type === 'slip_konfirmasi') {
                    printData = generateSlipKonfirmasi(job.job_data);
                } else if (job.job_type === 'surat_izin') {
                    printData = generateSuratIzin(job.job_data);
                }
                
                if (typeof qz !== 'undefined' && qz.websocket.isActive()) {
                    const printer = await qz.printers.getDefault();
                    const config = qz.configs.create(printer, { encoding: 'UTF-8' });
                    await qz.print(config, printData);
                    addLog(`✅ Job #${job.id} tercetak ke ${printer}`, 'success');
                } else {
                    addLog(`⚠️ QZ tidak aktif, job #${job.id} ditandai selesai`, 'warning');
                }
                
                await fetch(`/api/print-queue/${job.id}/complete`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                addLog(`✅ Job #${job.id} berhasil`, 'success');
            } catch (e) {
                addLog(`❌ Job #${job.id} gagal: ${e.message}`, 'error');
                await fetch(`/api/print-queue/${job.id}/fail?error=${encodeURIComponent(e.message)}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            }
            setStatus('active', 'Server Aktif - Menunggu antrian...');
            updateStats();
        }
        
        function generateSlipKonfirmasi(data) {
            const ESC = '\x1B', GS = '\x1D';
            const kode = data.kode_konfirmasi || '000000';
            
            // Build ESC/POS QR code commands (GS ( k)
            const qrData = kode;
            const dataLen = qrData.length + 3;
            const pL = dataLen % 256;
            const pH = Math.floor(dataLen / 256);
            
            let c = [];
            // Reset printer
            c.push({ type: 'raw', format: 'plain', data: ESC + '@' });
            // Center align
            c.push({ type: 'raw', format: 'plain', data: ESC + 'a\x01' });
            // Bold + Double height for name
            c.push({ type: 'raw', format: 'plain', data: ESC + 'E\x01' + GS + '!\x10' });
            c.push({ type: 'raw', format: 'plain', data: (data.nama_santri || '-').substring(0, 20) + '\n' });
            // Normal size
            c.push({ type: 'raw', format: 'plain', data: GS + '!\x00' + ESC + 'E\x00' });
            c.push({ type: 'raw', format: 'plain', data: 'Kelas ' + (data.kelas || '-') + '\n' });
            c.push({ type: 'raw', format: 'plain', data: '--------------------------------\n' });
            // Left align for details
            c.push({ type: 'raw', format: 'plain', data: ESC + 'a\x00' });
            c.push({ type: 'raw', format: 'plain', data: 'Keperluan: ' + (data.judul || '-').substring(0, 20) + '\n' });
            let batas = data.batas_waktu ? new Date(data.batas_waktu).toLocaleString('id-ID', {day:'2-digit',month:'2-digit',hour:'2-digit',minute:'2-digit'}) : '-';
            c.push({ type: 'raw', format: 'plain', data: 'Batas    : ' + batas + '\n' });
            c.push({ type: 'raw', format: 'plain', data: '--------------------------------\n' });
            // Center for QR and code
            c.push({ type: 'raw', format: 'plain', data: ESC + 'a\x01' });
            
            // QR Code via ESC/POS native commands
            // Model select (model 2)
            c.push({ type: 'raw', format: 'plain', data: GS + '(k\x04\x00\x31\x41\x32\x00' });
            // Size (module size 4)
            c.push({ type: 'raw', format: 'plain', data: GS + '(k\x03\x00\x31\x43\x04' });
            // Error correction (L)
            c.push({ type: 'raw', format: 'plain', data: GS + '(k\x03\x00\x31\x45\x30' });
            // Store data
            c.push({ type: 'raw', format: 'plain', data: GS + '(k' + String.fromCharCode(pL, pH) + '\x31\x50\x30' + qrData });
            // Print QR
            c.push({ type: 'raw', format: 'plain', data: GS + '(k\x03\x00\x31\x51\x30' });
            
            c.push({ type: 'raw', format: 'plain', data: '\n' });
            // Kode besar
            c.push({ type: 'raw', format: 'plain', data: ESC + 'E\x01' + GS + '!\x11' });
            c.push({ type: 'raw', format: 'plain', data: kode + '\n' });
            c.push({ type: 'raw', format: 'plain', data: GS + '!\x00' + ESC + 'E\x00' });
            c.push({ type: 'raw', format: 'plain', data: 'Kode Konfirmasi\n--------------------------------\n' });
            c.push({ type: 'raw', format: 'plain', data: 'Scan QR/input kode di:\n/konfirmasi-kembali\n\n\n\n' });
            // Cut paper
            c.push({ type: 'raw', format: 'plain', data: GS + 'V\x00' });
            return c;
        }
        
        function generateSuratIzin(data) {
            return [{ type: 'raw', format: 'plain', data: 'Surat Izin: ' + (data.nomor_surat || '-') + '\n\n\n' }];
        }

        updateStats();
    </script>
</body>
</html>
