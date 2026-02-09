@extends('layouts.app')

@section('title', 'Scan QR')

@push('styles')
<style>
    .scanner-container {
        max-width: 500px;
        margin: 0 auto;
    }
    
    .scanner-box {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px var(--shadow);
        border: 1px solid var(--border-color);
    }
    
    #reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
    }
    
    #reader video {
        border-radius: 12px;
    }
    
    .scan-instruction {
        background: var(--hover-bg);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: var(--text-muted);
        margin: 1.5rem 0;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border-color);
    }
    
    .divider span {
        padding: 0 1rem;
        font-size: 0.85rem;
    }
    
    .kode-input {
        font-size: 1.25rem;
        padding: 1rem;
        text-align: center;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 12px;
        border: 2px solid var(--border-color);
    }
    
    .kode-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .result-box {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .result-box.error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .result-box.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    /* Modal styling */
    .izin-detail-modal .modal-content {
        border-radius: 16px;
        border: none;
    }
    
    .izin-detail-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-color), #60a5fa);
        color: white;
        border-radius: 16px 16px 0 0;
        border: none;
    }
    
    .izin-detail-modal .btn-close {
        filter: brightness(0) invert(1);
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    
    .detail-value {
        font-weight: 600;
        text-align: right;
        color: var(--text-primary);
    }
    
    .badge-kategori {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-keluar {
        background: #fef3c7;
        color: #b45309;
    }
    
    .badge-pulang {
        background: #dbeafe;
        color: #1e40af;
    }
</style>
@endpush

@section('content')
<div class="scanner-container">
    <h4 class="fw-bold text-center mb-4"><i class="fas fa-qrcode me-2"></i>Scan QR</h4>
    
    <div class="scanner-box">
        <!-- Scanner Area -->
        <div id="scanner-area" class="d-none">
            <div class="scan-instruction mb-3">
                <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                <p class="mb-0 small text-muted">Arahkan kamera ke QR Code slip izin santri</p>
            </div>
            
            <div id="reader" class="mb-3"></div>
            
            <button type="button" id="btn-stop" class="btn btn-danger w-100">
                <i class="fas fa-stop me-1"></i> Tutup Kamera
            </button>
        </div>
        
        <!-- Start Button -->
        <div id="start-area">
            <div class="scan-instruction mb-3">
                <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                <h6 class="fw-bold mb-1">Konfirmasi Kembali</h6>
                <p class="mb-0 small text-muted">Scan QR Code atau masukkan kode unik untuk konfirmasi santri sudah kembali</p>
            </div>
            
            <button type="button" id="btn-start" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-camera me-2"></i> Mulai Scan
            </button>
        </div>
        
        <!-- Divider -->
        <div class="divider">
            <span>atau masukkan kode</span>
        </div>
        
        <!-- Manual Input -->
        <div class="mb-3">
            <input type="text" id="kode-input" class="form-control kode-input" placeholder="XXXXXX" maxlength="10">
        </div>
        <button type="button" id="btn-search" class="btn btn-outline-primary w-100">
            <i class="fas fa-search me-1"></i> Cari
        </button>
        
        <!-- Result Area -->
        <div id="result-area" class="mt-4 d-none">
            <div id="result-box" class="result-box">
                <i class="fas fa-check-circle fa-3x mb-2"></i>
                <h5 id="result-name" class="fw-bold mb-1">Nama Santri</h5>
                <p id="result-message" class="mb-0">Berhasil dikonfirmasi</p>
            </div>
        </div>
        
        <!-- Recent Confirmations -->
        <div class="mt-4">
            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-history me-1"></i> Konfirmasi Terakhir</h6>
            <div id="recent-scans" class="list-group">
                <div class="text-center text-muted small py-3">Belum ada konfirmasi</div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade izin-detail-modal" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clipboard-check me-2"></i>Detail Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div id="modal-avatar" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), #60a5fa); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 1.5rem; font-weight: bold;"></div>
                    <h5 id="modal-nama" class="fw-bold mt-2 mb-1"></h5>
                    <p id="modal-kelas" class="text-muted mb-0"></p>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Jenis Izin</span>
                    <span id="modal-kategori" class="detail-value"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Keperluan</span>
                    <span id="modal-judul" class="detail-value"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Keterangan</span>
                    <span id="modal-keterangan" class="detail-value" style="max-width: 200px; word-wrap: break-word;"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Izin</span>
                    <span id="modal-tanggal" class="detail-value"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Batas Waktu</span>
                    <span id="modal-batas" class="detail-value"></span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <input type="hidden" id="modal-id">
                <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success flex-fill" id="btn-konfirmasi">
                    <i class="fas fa-check me-1"></i> Konfirmasi Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4" style="overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                    <i class="fas fa-times text-white" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Tidak Ditemukan</h5>
                <p id="error-message" class="text-muted mb-4">Kode tidak ditemukan atau santri sudah dikonfirmasi</p>
                <button type="button" class="btn btn-light w-100 py-2 rounded-3" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let scanner = null;
        let isScanning = false;
        const recentScans = [];
        
        const btnStart = document.getElementById('btn-start');
        const btnStop = document.getElementById('btn-stop');
        const btnSearch = document.getElementById('btn-search');
        const btnKonfirmasi = document.getElementById('btn-konfirmasi');
        const scannerArea = document.getElementById('scanner-area');
        const startArea = document.getElementById('start-area');
        const resultArea = document.getElementById('result-area');
        const resultBox = document.getElementById('result-box');
        const kodeInput = document.getElementById('kode-input');
        
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        
        const csrfToken = '{{ csrf_token() }}';
        
        // Start scanning
        btnStart.addEventListener('click', function () {
            startArea.classList.add('d-none');
            scannerArea.classList.remove('d-none');
            
            scanner = new Html5Qrcode("reader");
            scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(function (err) {
                Swal.fire('Error Kamera', 'Tidak dapat mengakses kamera: ' + err, 'error');
                stopScanner();
            });
            isScanning = true;
        });
        
        // Stop scanning
        btnStop.addEventListener('click', stopScanner);
        
        function stopScanner() {
            if (scanner && isScanning) {
                scanner.stop().catch(() => { });
            }
            isScanning = false;
            scannerArea.classList.add('d-none');
            startArea.classList.remove('d-none');
        }
        
        function onScanSuccess(decodedText, decodedResult) {
            if (scanner && isScanning) {
                scanner.pause();
            }
            searchByKode(decodedText.trim());
        }
        
        function onScanFailure(error) {
            // Ignore - normal when no QR in frame
        }
        
        // Manual search button
        btnSearch.addEventListener('click', function() {
            const kode = kodeInput.value.trim();
            if (!kode) {
                Swal.fire('Kode Kosong', 'Masukkan kode konfirmasi terlebih dahulu', 'warning');
                return;
            }
            searchByKode(kode);
        });
        
        // Enter key on input
        kodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                btnSearch.click();
            }
        });
        
        // Search by kode
        function searchByKode(kode) {
            const data = new FormData();
            data.append('kode', kode);
            data.append('_token', csrfToken);
            
            fetch('/api/public/konfirmasi/search', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    showDetailModal(result.data);
                } else {
                    showErrorModal(result.message);
                }
                
                // Resume scanner after delay
                setTimeout(resumeScanner, 1000);
            })
            .catch(error => {
                showErrorModal('Terjadi kesalahan: ' + error.message);
                setTimeout(resumeScanner, 1000);
            });
        }
        
        // Show detail modal
        function showDetailModal(data) {
            document.getElementById('modal-id').value = data.id;
            document.getElementById('modal-avatar').textContent = (data.nama_lengkap || 'S').charAt(0).toUpperCase();
            document.getElementById('modal-nama').textContent = data.nama_lengkap;
            document.getElementById('modal-kelas').textContent = 'Kelas ' + data.kelas;
            
            const kategoriEl = document.getElementById('modal-kategori');
            kategoriEl.innerHTML = `<span class="badge-kategori ${data.kategori === 'izin_keluar' ? 'badge-keluar' : 'badge-pulang'}">${data.kategori_label}</span>`;
            
            document.getElementById('modal-judul').textContent = data.judul || '-';
            document.getElementById('modal-keterangan').textContent = data.keterangan || '-';
            document.getElementById('modal-tanggal').textContent = data.tanggal || '-';
            document.getElementById('modal-batas').textContent = data.batas_waktu || '-';
            
            detailModal.show();
        }
        
        // Show error modal
        function showErrorModal(message) {
            document.getElementById('error-message').textContent = message;
            errorModal.show();
        }
        
        // Konfirmasi button
        btnKonfirmasi.addEventListener('click', function() {
            const id = document.getElementById('modal-id').value;
            const nama = document.getElementById('modal-nama').textContent;
            
            btnKonfirmasi.disabled = true;
            btnKonfirmasi.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
            
            const data = new FormData();
            data.append('id', id);
            data.append('_token', csrfToken);
            
            fetch('/api/public/konfirmasi/direct', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(result => {
                detailModal.hide();
                btnKonfirmasi.disabled = false;
                btnKonfirmasi.innerHTML = '<i class="fas fa-check me-1"></i> Konfirmasi Kembali';
                
                if (result.status === 'success') {
                    showResult(true, nama, result.message);
                    addRecentScan(nama, true, result.data.terlambat ? 'Terlambat' : 'Tepat Waktu');
                    kodeInput.value = '';
                } else {
                    showResult(false, nama, result.message);
                    addRecentScan(nama, false, result.message);
                }
            })
            .catch(error => {
                detailModal.hide();
                btnKonfirmasi.disabled = false;
                btnKonfirmasi.innerHTML = '<i class="fas fa-check me-1"></i> Konfirmasi Kembali';
                showResult(false, 'Error', 'Terjadi kesalahan: ' + error.message);
            });
        });
        
        function showResult(success, name, message) {
            resultArea.classList.remove('d-none');
            resultBox.className = 'result-box' + (success ? '' : ' error');
            resultBox.innerHTML = `
                <i class="fas fa-${success ? 'check-circle' : 'times-circle'} fa-3x mb-2"></i>
                <h5 class="fw-bold mb-1">${name}</h5>
                <p class="mb-0">${message}</p>
            `;
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                resultArea.classList.add('d-none');
            }, 3000);
        }
        
        function addRecentScan(name, success, message) {
            recentScans.unshift({ 
                name, 
                success, 
                message, 
                time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) 
            });
            if (recentScans.length > 5) recentScans.pop();
            renderRecentScans();
        }
        
        function renderRecentScans() {
            const container = document.getElementById('recent-scans');
            if (recentScans.length === 0) {
                container.innerHTML = '<div class="text-center text-muted small py-3">Belum ada konfirmasi</div>';
                return;
            }
            container.innerHTML = recentScans.map(s => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-${s.success ? 'check text-success' : 'times text-danger'} me-2"></i>
                        <strong>${s.name}</strong>
                        <small class="text-muted d-block">${s.message}</small>
                    </div>
                    <small class="text-muted">${s.time}</small>
                </div>
            `).join('');
        }
        
        function resumeScanner() {
            if (scanner && isScanning) {
                try {
                    scanner.resume();
                } catch(e) {}
            }
        }
    });
</script>
@endpush