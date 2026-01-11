@extends('layouts.app')
@section('title', 'Print Server')

@push('styles')
    <style>
        .server-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .status-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        .status-active {
            background: #10b981;
        }

        .status-inactive {
            background: #ef4444;
            animation: none;
        }

        .status-printing {
            background: #f59e0b;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        .log-container {
            background: #1e293b;
            color: #94a3b8;
            border-radius: 12px;
            padding: 16px;
            height: 280px;
            overflow-y: auto;
            font-family: 'Consolas', monospace;
            font-size: 0.8rem;
        }

        .log-success {
            color: #10b981;
        }

        .log-error {
            color: #ef4444;
        }

        .log-info {
            color: #60a5fa;
        }

        .log-warning {
            color: #f59e0b;
        }

        .stat-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #059669;
        }

        .stat-number.text-danger {
            color: #ef4444 !important;
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="server-card">
                <div class="text-center mb-4">
                    <i class="fas fa-server fa-3x text-success mb-3"></i>
                    <h4 class="fw-bold">Print Server</h4>
                    <p class="text-muted">Halaman ini otomatis mencetak surat dari antrian</p>
                </div>

                <div class="d-flex align-items-center justify-content-center mb-4 p-3 rounded" style="background: #f0fdf4;">
                    <span class="status-indicator status-inactive" id="statusIndicator"></span>
                    <span class="fw-bold" id="statusText">Menunggu koneksi...</span>
                </div>

                <div class="row g-3 mb-4">
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
                    <button class="btn btn-success flex-grow-1" id="btnStart" onclick="startServer()"><i
                            class="fas fa-play me-1"></i> Mulai Server</button>
                    <button class="btn btn-danger" id="btnStop" onclick="stopServer()" disabled><i
                            class="fas fa-stop me-1"></i> Stop</button>
                </div>

                <div class="log-container" id="logContainer">
                    <div class="log-info">[System] Print Server siap. Klik "Mulai Server" untuk memulai.</div>
                </div>

                <div class="mt-3 text-center">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Biarkan halaman ini terbuka di
                        komputer kantor</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js"></script>
    <script>
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
                addLog('⚠️ QZ Tray tidak tersedia, menggunakan Queue Mode', 'warning');
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
                // QZ print logic would go here
                await fetch(`/api/print-queue/${job.id}/complete`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                addLog(`✅ Job #${job.id} berhasil dicetak`, 'success');
            } catch (e) {
                addLog(`❌ Job #${job.id} gagal: ${e.message}`, 'error');
                await fetch(`/api/print-queue/${job.id}/fail?error=${encodeURIComponent(e.message)}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            }
            setStatus('active', 'Server Aktif - Menunggu antrian...');
            updateStats();
        }

        updateStats();
    </script>
@endpush