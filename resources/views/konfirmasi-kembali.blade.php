<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Kembali - {{ config('app.name') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }

        body {
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            color: white;
            padding: 1.5rem 1rem;
        }

        .page-header .container {
            max-width: 1200px;
        }

        .page-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            opacity: 0.8;
            font-size: 0.85rem;
            margin: 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
        }

        /* Stats */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-number.warning { color: var(--warning-color); }
        .stat-number.danger { color: var(--danger-color); }

        .stat-label {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .filter-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-row .form-control, .filter-row .form-select {
            font-size: 0.85rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
        }

        .date-input {
            width: 150px;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 0.875rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 0.75rem;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            padding: 0.75rem;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        .table th:hover {
            background: #f1f5f9;
        }

        .table th .sort-icon {
            margin-left: 4px;
            opacity: 0.4;
        }

        .table th.sorted .sort-icon {
            opacity: 1;
            color: var(--primary-color);
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr {
            transition: background 0.15s;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Badges */
        .badge-kategori {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-izin-keluar {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-izin-pulang {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-status {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-tepat-waktu {
            background: #d1fae5;
            color: #047857;
        }

        .badge-terlambat {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-belum {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Avatar */
        .santri-cell {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .santri-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .santri-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
        }

        .santri-kelas {
            font-size: 0.7rem;
            color: #64748b;
        }

        .btn-konfirmasi {
            background: var(--success-color);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-konfirmasi:hover {
            background: #059669;
            color: white;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-icon {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        /* Refresh button */
        .refresh-btn {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            border: none;
            color: white;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            z-index: 100;
        }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            transform: translateY(20px) scale(0.95);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        .modal.show .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-backdrop {
            transition: opacity 0.3s ease-out;
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        .modal-header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1rem;
        }

        .modal-title {
            font-size: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #1e293b; }

        .code-input {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.875rem;
            font-size: 1.25rem;
            text-align: center;
            letter-spacing: 6px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            text-transform: uppercase;
        }

        .code-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .btn-scan {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 1rem;
            color: #64748b;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-scan:hover {
            background: #f1f5f9;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-confirm {
            background: linear-gradient(135deg, var(--success-color), #34d399);
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .scanner-container {
            background: #f8fafc;
            border-radius: 10px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Success Popup Animation */
        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: background 0.3s ease-out;
        }

        .success-overlay.show {
            background: rgba(0,0,0,0.8);
        }

        .success-content {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            max-width: 350px;
            margin: 1rem;
            transform: scale(0.8) translateY(20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .success-content.show {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        #qrReader { width: 100%; border-radius: 10px; overflow: hidden; }

        /* Category Tabs */
        .category-tabs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .category-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .category-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .category-tab.active {
            border-color: var(--primary-color);
            background: #eff6ff;
        }

        .category-tab .tab-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .category-tab.tab-keluar .tab-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .category-tab.tab-pulang .tab-icon {
            background: #ffedd5;
            color: #ea580c;
        }

        .category-tab.tab-semua .tab-icon {
            background: #e0f2fe;
            color: #0284c7;
        }

        .category-tab .tab-text {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .category-tab .tab-count {
            background: #e2e8f0;
            color: #475569;
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .category-tab.active .tab-count {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .stats-row { grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
            .stat-card { padding: 0.75rem; }
            .stat-number { font-size: 1.25rem; }
            .filter-row { flex-direction: column; }
            .search-input, .date-input { width: 100%; min-width: auto; }
            .table th, .table td { padding: 0.5rem; font-size: 0.75rem; }
            .hide-mobile { display: none !important; }
            .category-tabs { gap: 0.5rem; }
            .category-tab { padding: 0.75rem 0.5rem; flex-direction: column; gap: 0.5rem; }
            .category-tab .tab-icon { width: 36px; height: 36px; font-size: 1rem; }
            .category-tab .tab-text { font-size: 0.8rem; }
        }
    </style>
</head>

<body>
    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><i class="fas fa-check-circle me-2"></i>Konfirmasi Kembali</h1>
            <p class="page-subtitle">Pilih nama santri untuk konfirmasi sudah kembali ke pondok</p>
        </div>
    </div>

    <div class="main-container">
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number" id="statTotal">-</div>
                <div class="stat-label">Sedang Izin</div>
            </div>
            <div class="stat-card">
                <div class="stat-number warning" id="statMendekat">-</div>
                <div class="stat-label">Hampir Batas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number danger" id="statTerlambat">-</div>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs">
            <div class="category-tab tab-semua active" onclick="setCategory('semua')">
                <div class="tab-icon"><i class="fas fa-list"></i></div>
                <span class="tab-text">Semua</span>
                <span class="tab-count" id="countSemua">0</span>
            </div>
            <div class="category-tab tab-keluar" onclick="setCategory('izin_keluar')">
                <div class="tab-icon"><i class="fas fa-sign-out-alt"></i></div>
                <span class="tab-text">Izin Keluar</span>
                <span class="tab-count" id="countKeluar">0</span>
            </div>
            <div class="category-tab tab-pulang" onclick="setCategory('izin_pulang')">
                <div class="tab-icon"><i class="fas fa-home"></i></div>
                <span class="tab-text">Izin Pulang</span>
                <span class="tab-count" id="countPulang">0</span>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <div class="filter-row">
                <div class="input-group search-input">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama santri...">
                </div>
                <input type="date" id="dateFrom" class="form-control date-input" title="Dari tanggal">
                <input type="date" id="dateTo" class="form-control date-input" title="Sampai tanggal">
                <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Reset
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-list me-2"></i>Daftar Santri Izin Aktif</h5>
                <button class="btn btn-sm btn-light" onclick="loadSantri()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table" id="santriTable">
                    <thead>
                        <tr>
                            <th data-sort="nama" onclick="sortTable('nama')">
                                Santri <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th data-sort="kategori" onclick="sortTable('kategori')">
                                Jenis <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th data-sort="judul" onclick="sortTable('judul')" class="hide-mobile">
                                Keperluan <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th data-sort="tanggal" onclick="sortTable('tanggal')" class="hide-mobile">
                                Tgl Pergi <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th data-sort="batas" onclick="sortTable('batas')">
                                Batas <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th data-sort="status" onclick="sortTable('status')">
                                Status <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="santriBody">
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <span class="ms-2 text-muted">Memuat data...</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <button class="refresh-btn" onclick="loadSantri()">
        <i class="fas fa-sync-alt"></i>
    </button>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Konfirmasi Kembali</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="info-section mb-3">
                        <div class="info-row">
                            <span class="info-label">Nama</span>
                            <span class="info-value" id="modalNama">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kelas</span>
                            <span class="info-value" id="modalKelas">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Jenis</span>
                            <span class="info-value" id="modalKategori">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Keperluan</span>
                            <span class="info-value" id="modalJudul">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Batas</span>
                            <span class="info-value" id="modalBatas">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span id="modalStatus">-</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.9rem;">Masukkan Kode Konfirmasi</label>
                        <input type="text" id="inputKode" class="form-control code-input" placeholder="ABC123" maxlength="6" autocomplete="off">
                    </div>

                    <div class="divider">atau</div>

                    <div id="scannerArea" class="d-none">
                        <div class="scanner-container">
                            <div id="qrReader"></div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="stopScanner()">
                            <i class="fas fa-times me-1"></i>Tutup Kamera
                        </button>
                    </div>

                    <div id="scannerBtn">
                        <button type="button" class="btn btn-scan" onclick="startScanner()">
                            <i class="fas fa-qrcode fa-lg mb-1 d-block"></i>
                            <span style="font-size:0.85rem;">Scan QR Code</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-confirm" onclick="submitKonfirmasi()">
                        <i class="fas fa-check me-1"></i>Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let allData = [];
        let filteredData = [];
        let selectedId = null;
        let html5QrCode = null;
        let currentSort = { field: null, asc: true };
        let currentCategory = 'semua';
        const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

        document.addEventListener('DOMContentLoaded', function() {
            loadSantri();
            
            // Event listeners for filters
            document.getElementById('searchInput').addEventListener('input', applyFilters);
            document.getElementById('dateFrom').addEventListener('change', applyFilters);
            document.getElementById('dateTo').addEventListener('change', applyFilters);
        });

        function setCategory(cat) {
            currentCategory = cat;
            
            // Update active state
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            if (cat === 'semua') {
                document.querySelector('.tab-semua').classList.add('active');
            } else if (cat === 'izin_keluar') {
                document.querySelector('.tab-keluar').classList.add('active');
            } else if (cat === 'izin_pulang') {
                document.querySelector('.tab-pulang').classList.add('active');
            }
            
            applyFilters();
        }

        function updateCategoryCounts() {
            const semua = allData.length;
            const keluar = allData.filter(s => s.kategori === 'izin_keluar').length;
            const pulang = allData.filter(s => s.kategori === 'izin_pulang').length;
            
            document.getElementById('countSemua').textContent = semua;
            document.getElementById('countKeluar').textContent = keluar;
            document.getElementById('countPulang').textContent = pulang;
        }

        function getTimeStatus(batasWaktu) {
            if (!batasWaktu) return { status: 'belum', label: 'Tidak ada batas', class: 'badge-belum', sortValue: 0 };
            
            const now = new Date();
            const batas = new Date(batasWaktu);
            const diffMs = batas - now;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMs < 0) {
                // Sudah melewati batas waktu
                const telatMins = Math.abs(diffMins);
                if (telatMins < 60) {
                    return { status: 'terlambat', label: `Lewat ${telatMins}m`, class: 'badge-terlambat', sortValue: -telatMins };
                } else {
                    const hours = Math.floor(telatMins / 60);
                    return { status: 'terlambat', label: `Lewat ${hours}j`, class: 'badge-terlambat', sortValue: -telatMins };
                }
            } else if (diffMins <= 30) {
                // Hampir batas waktu (30 menit)
                return { status: 'mendekat', label: `${diffMins}m lagi`, class: 'badge-terlambat', sortValue: diffMins };
            } else if (diffMins <= 60) {
                // Sisa waktu kurang dari 1 jam
                return { status: 'menunggu', label: `${diffMins}m lagi`, class: 'badge-belum', sortValue: diffMins };
            } else {
                // Masih banyak waktu
                const hours = Math.floor(diffMins / 60);
                const mins = diffMins % 60;
                return { status: 'menunggu', label: `${hours}j ${mins}m lagi`, class: 'badge-belum', sortValue: diffMins };
            }
        }

        async function loadSantri() {
            const tbody = document.getElementById('santriBody');
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div><span class="ms-2 text-muted">Memuat...</span></td></tr>`;

            try {
                const res = await fetch('/api/public/santri-izin-aktif');
                const data = await res.json();

                if (data.status === 'success') {
                    allData = data.data.map(s => {
                        const timeStatus = getTimeStatus(s.batas_waktu_raw);
                        return { ...s, timeStatus };
                    });
                    updateCategoryCounts();
                    applyFilters();
                } else {
                    showEmpty('Tidak ada data');
                }
            } catch (e) {
                showEmpty('Gagal memuat data: ' + e.message);
            }
        }

        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;

            filteredData = allData.filter(s => {
                // Category filter
                if (currentCategory !== 'semua' && s.kategori !== currentCategory) return false;
                
                // Search filter
                if (search && !s.nama_lengkap.toLowerCase().includes(search)) return false;
                
                // Date filters
                if (s.batas_waktu_raw) {
                    const itemDate = new Date(s.batas_waktu_raw).toISOString().split('T')[0];
                    if (dateFrom && itemDate < dateFrom) return false;
                    if (dateTo && itemDate > dateTo) return false;
                }
                
                return true;
            });

            if (currentSort.field) {
                sortData(currentSort.field, currentSort.asc);
            }

            renderTable();
            updateStats();
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            applyFilters();
        }

        function sortTable(field) {
            const th = document.querySelector(`th[data-sort="${field}"]`);
            
            // Toggle sort direction
            if (currentSort.field === field) {
                currentSort.asc = !currentSort.asc;
            } else {
                currentSort.field = field;
                currentSort.asc = true;
            }

            // Update UI
            document.querySelectorAll('th').forEach(t => {
                t.classList.remove('sorted');
                const icon = t.querySelector('.sort-icon');
                if (icon) icon.className = 'fas fa-sort sort-icon';
            });
            
            th.classList.add('sorted');
            const icon = th.querySelector('.sort-icon');
            icon.className = `fas fa-sort-${currentSort.asc ? 'up' : 'down'} sort-icon`;

            sortData(field, currentSort.asc);
            renderTable();
        }

        function sortData(field, asc) {
            filteredData.sort((a, b) => {
                let valA, valB;
                
                switch(field) {
                    case 'nama': valA = a.nama_lengkap; valB = b.nama_lengkap; break;
                    case 'kategori': valA = a.kategori; valB = b.kategori; break;
                    case 'judul': valA = a.judul || ''; valB = b.judul || ''; break;
                    case 'tanggal': valA = a.batas_waktu_raw || ''; valB = b.batas_waktu_raw || ''; break;
                    case 'batas': valA = a.batas_waktu_raw || ''; valB = b.batas_waktu_raw || ''; break;
                    case 'status': valA = a.timeStatus.sortValue; valB = b.timeStatus.sortValue; break;
                    default: return 0;
                }

                if (typeof valA === 'string') {
                    return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
                }
                return asc ? valA - valB : valB - valA;
            });
        }

        function renderTable() {
            const tbody = document.getElementById('santriBody');
            
            if (filteredData.length === 0) {
                showEmpty('Tidak ada data yang sesuai');
                return;
            }

            tbody.innerHTML = filteredData.map(s => `
                <tr>
                    <td>
                        <div class="santri-cell">
                            <div class="santri-avatar">${s.nama_lengkap.charAt(0)}</div>
                            <div>
                                <div class="santri-name">${s.nama_lengkap}</div>
                                <div class="santri-kelas">Kelas ${s.kelas}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-kategori badge-${s.kategori.replace('_', '-')}">${s.kategori === 'izin_keluar' ? 'Keluar' : 'Pulang'}</span></td>
                    <td class="hide-mobile">${s.judul || '-'}</td>
                    <td class="hide-mobile">${s.tanggal || '-'}</td>
                    <td>${s.batas_waktu || '-'}</td>
                    <td><span class="badge-status ${s.timeStatus.class}">${s.timeStatus.label}</span></td>
                    <td>
                        <button class="btn-konfirmasi" onclick="openModal(${s.id})" title="Konfirmasi">
                            <i class="fas fa-check"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function showEmpty(msg) {
            document.getElementById('santriBody').innerHTML = `
                <tr><td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                        <p class="text-muted mb-0">${msg}</p>
                    </div>
                </td></tr>
            `;
        }

        function updateStats() {
            let total = 0, mendekat = 0, terlambat = 0;
            allData.forEach(s => {
                total++;
                if (s.timeStatus.status === 'terlambat') terlambat++;
                else if (s.timeStatus.status === 'mendekat') mendekat++;
            });
            document.getElementById('statTotal').textContent = total;
            document.getElementById('statMendekat').textContent = mendekat;
            document.getElementById('statTerlambat').textContent = terlambat;
        }

        async function openModal(id) {
            selectedId = id;
            document.getElementById('inputKode').value = '';
            document.getElementById('scannerArea').classList.add('d-none');
            document.getElementById('scannerBtn').classList.remove('d-none');

            try {
                const res = await fetch(`/api/public/izin/${id}`);
                const data = await res.json();

                if (data.status === 'success') {
                    const d = data.data;
                    document.getElementById('modalNama').textContent = d.nama_lengkap;
                    document.getElementById('modalKelas').textContent = d.kelas;
                    document.getElementById('modalKategori').textContent = d.kategori_label;
                    document.getElementById('modalJudul').textContent = d.judul || '-';
                    document.getElementById('modalBatas').textContent = d.batas_waktu || '-';
                    
                    const timeStatus = getTimeStatus(d.batas_waktu_raw);
                    document.getElementById('modalStatus').innerHTML = `<span class="badge-status ${timeStatus.class}">${timeStatus.label}</span>`;
                    
                    modal.show();
                }
            } catch (e) {
                alert('Gagal memuat detail: ' + e.message);
            }
        }

        function startScanner() {
            document.getElementById('scannerBtn').classList.add('d-none');
            document.getElementById('scannerArea').classList.remove('d-none');

            html5QrCode = new Html5Qrcode("qrReader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 200, height: 200 } },
                (decodedText) => {
                    document.getElementById('inputKode').value = decodedText;
                    stopScanner();
                    submitKonfirmasi();
                },
                () => {}
            ).catch(err => {
                alert('Tidak dapat mengakses kamera');
                stopScanner();
            });
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) html5QrCode.stop();
            document.getElementById('scannerArea').classList.add('d-none');
            document.getElementById('scannerBtn').classList.remove('d-none');
        }

        async function submitKonfirmasi() {
            const kode = document.getElementById('inputKode').value.trim();
            if (!kode || kode.length < 6) {
                alert('Masukkan kode konfirmasi 6 karakter!');
                return;
            }

            try {
                const res = await fetch('/api/public/konfirmasi-kembali', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id: selectedId, kode: kode })
                });

                const data = await res.json();

                if (data.status === 'success') {
                    modal.hide();
                    showSuccess(data.message, data.data);
                    loadSantri();
                } else {
                    alert(data.message || 'Konfirmasi gagal');
                }
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }

        function showSuccess(message, data) {
            const isLate = data.terlambat || false;
            const popup = document.createElement('div');
            popup.className = 'success-popup';
            popup.innerHTML = `
                <div class="success-overlay">
                    <div class="success-content">
                        <div style="color:${isLate ? '#ef4444' : '#10b981'};font-size:4rem;margin-bottom:0.75rem;">
                            <i class="fas ${isLate ? 'fa-clock' : 'fa-check-circle'}"></i>
                        </div>
                        <h3 style="color:#1e293b;margin-bottom:0.5rem;font-size:1.1rem;">${data.nama}</h3>
                        <p style="color:#64748b;margin-bottom:0.75rem;font-size:0.9rem;">Kembali: ${data.waktu_kembali}</p>
                        <span class="badge-status ${isLate ? 'badge-terlambat' : 'badge-tepat-waktu'}" style="font-size:0.85rem;padding:0.4rem 0.8rem;">
                            ${isLate ? 'Terlambat' : 'Tepat Waktu'}
                        </span>
                    </div>
                </div>
            `;
            document.body.appendChild(popup);
            
            // Trigger animation
            requestAnimationFrame(() => {
                popup.querySelector('.success-overlay').classList.add('show');
                popup.querySelector('.success-content').classList.add('show');
            });
            
            // Fade out and remove
            setTimeout(() => {
                popup.querySelector('.success-overlay').classList.remove('show');
                popup.querySelector('.success-content').classList.remove('show');
                setTimeout(() => popup.remove(), 300);
            }, 2200);
        }

        document.getElementById('inputKode').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        document.getElementById('modalKonfirmasi').addEventListener('hidden.bs.modal', stopScanner);
        
        setInterval(loadSantri, 30000);
    </script>
</body>

</html>
