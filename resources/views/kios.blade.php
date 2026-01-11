<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi RFID - {{ config('app.name') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            min-height: 100vh;
            color: white;
            overflow: hidden;
        }

        .kiosk-container {
            height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem;
            padding: 2rem;
        }

        .rfid-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .rfid-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rfid-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.7;
            }
        }

        .rfid-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .rfid-subtitle {
            opacity: 0.7;
            margin-bottom: 2rem;
        }

        .rfid-input {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 1.25rem;
            font-size: 1.5rem;
            color: white;
            text-align: center;
            width: 100%;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .rfid-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 1px;
            font-size: 1rem;
        }

        .rfid-input:focus {
            outline: none;
            border-color: var(--success-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.3);
        }

        .jadwal-select-wrapper {
            margin-top: 1.5rem;
            width: 100%;
        }

        .jadwal-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
        }

        .jadwal-select:focus {
            outline: none;
            border-color: var(--success-color);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .jadwal-select option {
            background: #1e293b;
            color: white;
            padding: 0.5rem;
        }

        .result-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: white;
            border-radius: 24px;
            padding: 3rem 4rem;
            text-align: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .result-popup.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .result-popup.success {
            color: var(--success-color);
        }

        .result-popup.error {
            color: var(--danger-color);
        }

        .result-popup.warning {
            color: var(--warning-color);
        }

        .result-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        .result-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .result-class {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .result-status {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .live-panel {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .live-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .live-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .live-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-box {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        .live-clock {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
        }

        .live-date {
            text-align: center;
            opacity: 0.7;
            margin-bottom: 1.5rem;
        }

        .live-list {
            max-height: 320px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 1rem;
        }

        .live-list::-webkit-scrollbar {
            width: 8px;
        }

        .live-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }

        .live-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .live-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .live-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .live-info {
            flex: 1;
        }

        .live-name {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .live-meta {
            opacity: 0.7;
            font-size: 0.9rem;
        }

        .live-time {
            text-align: right;
        }

        .live-time-value {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .live-status {
            font-size: 0.8rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .status-hadir {
            background: var(--success-color);
        }

        .status-terlambat {
            background: var(--warning-color);
        }

        .status-pulang {
            background: var(--primary-color);
        }

        .empty-list {
            text-align: center;
            padding: 3rem;
            opacity: 0.5;
        }

        .filter-bar {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.75rem;
            border-radius: 12px;
        }

        .filter-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: white;
            font-size: 0.9rem;
            flex: 1;
            min-width: 150px;
        }

        .filter-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .filter-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .filter-select option {
            background: #1e293b;
            color: white;
        }

        .filter-btn {
            background: rgba(239, 68, 68, 0.3);
            border: 1px solid rgba(239, 68, 68, 0.5);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: rgba(239, 68, 68, 0.5);
        }

        @media (max-width: 991px) {
            .kiosk-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
            }

            .rfid-card {
                padding: 2rem;
            }

            .rfid-icon {
                font-size: 3rem;
            }

            .rfid-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="kiosk-container">
        <div class="rfid-panel">
            <div class="rfid-card">
                <div class="rfid-icon"><i class="fas fa-id-card"></i></div>
                <h1 class="rfid-title">Tempelkan Kartu</h1>
                <p class="rfid-subtitle">Arahkan kartu RFID ke reader</p>
                <form id="rfidForm" autocomplete="off">
                    <input type="text" id="rfidInput" class="rfid-input" placeholder="Menunggu kartu..." autofocus
                        autocomplete="off">
                </form>
                <div class="jadwal-select-wrapper">
                    <select id="jadwalSelect" class="jadwal-select">
                        <option value="">-- Pilih Jenis Absen --</option>
                        @foreach($jadwalList as $j)
                            <option value="{{ $j->id }}" data-type="{{ $j->type }}">{{ $j->name }}
                                ({{ date('H:i', strtotime($j->start_time)) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="live-panel">
            <div class="live-header">
                <div class="live-title"><i class="fas fa-broadcast-tower me-2"></i>Live Attendance</div>
                <div class="live-stats">
                    <div class="stat-box">
                        <div class="stat-number" id="statTotal">{{ $todayTotal }}</div>
                        <div class="stat-label">Hadir Hari Ini</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $totalSiswa }}</div>
                        <div class="stat-label">Total Santri</div>
                    </div>
                </div>
            </div>

            <div class="live-clock" id="liveClock">--:--:--</div>
            <div class="live-date" id="liveDate">-</div>

            <div class="filter-bar mb-3">
                <div class="d-flex gap-2 flex-wrap">
                    <input type="text" id="filterSearch" class="filter-input" placeholder="Cari nama..."
                        autocomplete="off">
                    <select id="filterGender" class="filter-select">
                        <option value="">Semua JK</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    <select id="filterStatus" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="pulang">Pulang</option>
                    </select>
                    <button type="button" class="filter-btn" onclick="clearFilters()"><i
                            class="fas fa-times"></i></button>
                </div>
            </div>

            <div class="live-list" id="liveList">
                @forelse($recentAttendances as $a)
                    <div class="live-item" data-name="{{ strtolower($a->nama_lengkap) }}"
                        data-gender="{{ $a->jenis_kelamin }}" data-status="{{ $a->status }}">
                        <div class="live-avatar">{{ strtoupper(substr($a->nama_lengkap, 0, 1)) }}</div>
                        <div class="live-info">
                            <div class="live-name">{{ $a->nama_lengkap }}</div>
                            <div class="live-meta">Kelas {{ $a->kelas }} | {{ $a->nomor_induk ?? '-' }}</div>
                        </div>
                        <div class="live-time">
                            <div class="live-time-value">{{ date('H:i', strtotime($a->attendance_time)) }}</div>
                            <span class="live-status status-{{ $a->status }}">{{ ucfirst($a->status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-list">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Belum ada absensi hari ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0" style="background: #1e293b; color: white;">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Masukkan Sandi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small opacity-75 mb-3">Untuk mengubah jenis absensi, masukkan sandi kiosk:</p>
                    <input type="password" id="kioskPassword" class="form-control form-control-lg text-center"
                        placeholder="****" autocomplete="off" maxlength="10">
                    <div id="passwordError" class="text-danger small mt-2 d-none">Sandi salah!</div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnVerifyPassword">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rfidInput = document.getElementById('rfidInput');
            const KIOSK_PASSWORD = '{{ $kioskPassword }}';
            let selectedJadwal = null;
            let jadwalLocked = false;
            let isProcessing = false;
            let todayTotal = {{ $todayTotal }};

            const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));

            function updateClock() {
                const now = new Date();
                document.getElementById('liveClock').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
                document.getElementById('liveDate').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            }
            updateClock();
            setInterval(updateClock, 1000);

            function focusInput() {
                if (isProcessing) return;
                if (document.getElementById('passwordModal').classList.contains('show')) return;
                const activeEl = document.activeElement;
                if (activeEl && (activeEl.id === 'filterSearch' || activeEl.id === 'filterGender' || activeEl.id === 'filterStatus' || activeEl.id === 'jadwalSelect' || activeEl.id === 'kioskPassword' || activeEl.tagName === 'SELECT')) return;
                rfidInput.focus();
            }

            document.addEventListener('click', function (e) {
                if (e.target.tagName === 'SELECT' || e.target.tagName === 'INPUT' || e.target.closest('.filter-bar') || e.target.closest('.jadwal-select-wrapper')) return;
                focusInput();
            });
            setInterval(() => { if (document.activeElement === document.body) focusInput(); }, 2000);

            const jadwalSelect = document.getElementById('jadwalSelect');
            let pendingJadwalValue = null;

            jadwalSelect.addEventListener('change', function () {
                const newValue = this.value;
                const selectedOption = this.options[this.selectedIndex];
                if (!newValue) return;
                if (!jadwalLocked) {
                    selectedJadwal = { id: newValue, type: selectedOption.dataset.type };
                    jadwalLocked = true;
                    focusInput();
                    return;
                }
                if (selectedJadwal && newValue === selectedJadwal.id) { focusInput(); return; }
                pendingJadwalValue = newValue;
                this.value = selectedJadwal ? selectedJadwal.id : '';
                document.getElementById('kioskPassword').value = '';
                document.getElementById('passwordError').classList.add('d-none');
                passwordModal.show();
                setTimeout(() => document.getElementById('kioskPassword').focus(), 300);
            });

            document.getElementById('btnVerifyPassword').addEventListener('click', verifyPassword);
            document.getElementById('kioskPassword').addEventListener('keyup', function (e) { if (e.key === 'Enter') verifyPassword(); });

            function verifyPassword() {
                const pwd = document.getElementById('kioskPassword').value;
                if (pwd === KIOSK_PASSWORD) {
                    passwordModal.hide();
                    if (pendingJadwalValue) {
                        jadwalSelect.value = pendingJadwalValue;
                        const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
                        selectedJadwal = { id: pendingJadwalValue, type: selectedOption.dataset.type };
                        pendingJadwalValue = null;
                        focusInput();
                    }
                } else {
                    document.getElementById('passwordError').classList.remove('d-none');
                }
            }

            document.getElementById('rfidForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const rfid = rfidInput.value.trim();
                if (!rfid || isProcessing) return;
                if (!selectedJadwal) { alert('Pilih jadwal terlebih dahulu!'); rfidInput.value = ''; return; }
                processRfid(rfid);
            });

            async function processRfid(rfid) {
                isProcessing = true;
                try {
                    const res = await fetch('/api/attendance/rfid', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ rfid: rfid, jadwal_id: selectedJadwal.id })
                    });
                    const data = await res.json();
                    if (data.success) {
                        todayTotal++;
                        document.getElementById('statTotal').textContent = todayTotal;
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal absen');
                    }
                } catch (e) {
                    alert('Error: ' + e.message);
                }
                rfidInput.value = '';
                isProcessing = false;
                focusInput();
            }

            window.clearFilters = function () {
                document.getElementById('filterSearch').value = '';
                document.getElementById('filterGender').value = '';
                document.getElementById('filterStatus').value = '';
                applyFilters();
            };

            function applyFilters() {
                const search = document.getElementById('filterSearch').value.toLowerCase();
                const gender = document.getElementById('filterGender').value;
                const status = document.getElementById('filterStatus').value;
                document.querySelectorAll('.live-item').forEach(item => {
                    const name = item.dataset.name;
                    const g = item.dataset.gender;
                    const s = item.dataset.status;
                    const show = (search === '' || name.includes(search)) && (gender === '' || g === gender) && (status === '' || s === status);
                    item.style.display = show ? '' : 'none';
                });
            }

            document.getElementById('filterSearch').addEventListener('input', applyFilters);
            document.getElementById('filterGender').addEventListener('change', applyFilters);
            document.getElementById('filterStatus').addEventListener('change', applyFilters);
        });
    </script>
</body>

</html>