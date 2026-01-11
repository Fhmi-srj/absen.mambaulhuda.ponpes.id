@extends('layouts.app')

@section('title', $pageTitle ?? 'Aktivitas Santri')

@push('css')
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('styles')
    <style>
        .fw-medium {
            font-weight: 500;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .text-primary-custom {
            color: var(--primary-color);
        }

        /* DataTables Sorting */
        table.dataTable thead th.sorting,
        table.dataTable thead th.sorting_asc,
        table.dataTable thead th.sorting_desc {
            background-image: none !important;
            padding-right: 28px !important;
            cursor: pointer;
        }

        table.dataTable thead th.sorting:after,
        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            content: "\f0dc" !important;
            font-family: "Font Awesome 5 Free" !important;
            font-weight: 900 !important;
            position: absolute !important;
            right: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            opacity: 0.3 !important;
            font-size: 0.7rem !important;
            bottom: auto !important;
        }

        table.dataTable thead th.sorting_asc:after {
            content: "\f0de" !important;
            opacity: 1 !important;
            color: var(--primary-color) !important;
        }

        table.dataTable thead th.sorting_desc:after {
            content: "\f0dd" !important;
            opacity: 1 !important;
            color: var(--primary-color) !important;
        }

        table.dataTable thead th:hover {
            background-color: #f1f5f9 !important;
        }

        .section-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 12px 45px 12px 15px;
            height: 48px;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.2s;
        }

        .search-input:focus {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(134, 89, 241, 0.1);
            outline: none;
        }

        .btn-qr-scan {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            height: 34px;
            width: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            color: var(--primary-color);
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-qr-scan:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        .student-info-empty {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background-color: #f8fafc;
        }

        .student-card-active {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-radius: 16px;
            color: white;
            padding: 25px;
            position: relative;
            box-shadow: 0 10px 20px -5px rgba(134, 89, 241, 0.4);
        }

        .student-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding-bottom: 6px;
        }

        .student-info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .student-label {
            opacity: 0.85;
            font-weight: 400;
            font-size: 0.85rem;
        }

        .student-value {
            font-weight: 600;
        }

        .cat-btn {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            transition: all 0.2s;
            height: 100%;
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            color: #334155;
            position: relative;
            overflow: visible;
        }

        .cat-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .cat-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .theme-sakit {
            color: #ef4444;
            background: #fef2f2;
        }

        .theme-izin-keluar {
            color: #f59e0b;
            background: #fffbeb;
        }

        .theme-izin-pulang {
            color: #f97316;
            background: #fff7ed;
        }

        .theme-sambangan {
            color: #10b981;
            background: #ecfdf5;
        }

        .theme-pelanggaran {
            color: #db2777;
            background: #fdf2f8;
        }

        .theme-paket {
            color: #3b82f6;
            background: #eff6ff;
        }

        .theme-hafalan {
            color: #3b82f6;
            background: #dbeafe;
        }

        .theme-izin-sekolah {
            color: #059669;
            background: #d1fae5;
        }

        #table-aktivitas {
            width: 100% !important;
        }

        #table-aktivitas thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px;
            white-space: nowrap;
        }

        #table-aktivitas tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }

        .custom-select-filter {
            background-color: #f1f5f9;
            border: 1px solid transparent;
            font-weight: 600;
            color: #475569;
            padding: 0.5rem 2rem 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .custom-select-filter:focus {
            background-color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(134, 89, 241, 0.1);
        }

        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        .modal-dialog-scrollable .modal-body {
            max-height: calc(100vh - 200px);
        }

        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.025em;
            margin-bottom: 0.5rem;
        }

        .form-control-custom {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(134, 89, 241, 0.1);
            outline: none;
        }

        /* Photo Upload Component */
        .photo-upload-wrapper {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: #f8fafc;
            transition: all 0.3s;
        }

        .photo-upload-wrapper:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
        }

        .photo-upload-wrapper.has-preview {
            border-style: solid;
            border-color: #10b981;
            background: #ecfdf5;
        }

        .photo-upload-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-photo-upload {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-camera {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-camera:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-file {
            background: white;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-file:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .photo-preview-container {
            position: relative;
            display: inline-block;
            margin-top: 15px;
        }

        .photo-preview-container img {
            max-width: 100%;
            max-height: 180px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-remove-photo {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            border: 2px solid white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn-remove-photo:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .photo-upload-buttons {
                flex-direction: column;
            }

            .btn-photo-upload {
                width: 100%;
                justify-content: center;
            }
        }

        .foto-preview {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid #e2e8f0;
        }

        .autocomplete-list {
            position: absolute;
            z-index: 1050;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            background: white;
        }

        @media (max-width: 767.98px) {
            .card-header-custom {
                flex-direction: column;
                align-items: flex-start !important;
                padding: 1rem;
            }

            .header-tools-wrapper {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $role = auth()->user()->role;
        $isAdmin = $role === 'admin';
    @endphp

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fs-4 me-3"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fs-4 me-3"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h4 class="fw-bold mb-4"><i class="fas fa-clipboard-list me-2"></i>{{ $pageTitle ?? 'Monitoring Aktivitas' }}</h4>

    <div class="row g-4">
        <!-- KOLOM KIRI: INPUT DATA -->
        <div class="col-lg-4">
            <div class="card-custom h-100">
                <div class="card-body p-4">
                    <!-- PENCARIAN -->
                    <div class="mb-4 position-relative">
                        <div class="section-title">Pilih Siswa</div>
                        <div class="search-wrapper">
                            <input type="text" id="input_cari" class="search-input" placeholder="Cari nama atau NIS..."
                                autocomplete="off">
                            <button class="btn-qr-scan" id="btn_buka_kamera" title="Scan QR"><i
                                    class="fas fa-qrcode"></i></button>
                        </div>
                        <div id="hasil_autocomplete" class="list-group autocomplete-list d-none"></div>
                        <div id="area_kamera" class="mt-3 d-none rounded-3 overflow-hidden shadow-sm">
                            <div id="reader" style="width: 100%;"></div>
                            <button type="button" id="btn_tutup_kamera" class="btn btn-danger btn-sm w-100 rounded-0 mt-1">
                                <i class="fas fa-times me-1"></i> Tutup Kamera
                            </button>
                        </div>
                    </div>

                    <!-- CARD SISWA -->
                    <div id="card_siswa_wrapper" class="mb-4">
                        <div id="empty_card" class="student-info-empty">
                            <i class="far fa-id-card fa-3x mb-3" style="color: #cbd5e1;"></i>
                            <p class="mb-0 small text-muted fw-medium">Belum ada siswa dipilih</p>
                        </div>
                        <div id="card_siswa" class="student-card-active d-none">
                            <button type="button" id="btn_reset"
                                class="btn-close btn-close-white position-absolute top-0 end-0 m-3 opacity-75"></button>
                            <h4 id="lbl_nama" class="fw-bold mb-3 text-truncate pe-4">Nama Siswa</h4>
                            <div class="student-info-row"><span class="student-label">NIS</span><span class="student-value"
                                    id="lbl_nis">-</span></div>
                            <div class="student-info-row"><span class="student-label">Kelas</span><span
                                    class="student-value" id="lbl_kelas">-</span></div>
                            <div class="student-info-row"><span class="student-label">Alamat</span><span
                                    class="student-value text-truncate" style="max-width: 150px;" id="lbl_alamat">-</span>
                            </div>
                            <input type="hidden" id="selected_siswa_id">
                            <input type="hidden" id="selected_siswa_phone">
                        </div>
                    </div>

                    <!-- GRID TOMBOL KATEGORI -->
                    <div id="panel_menu">
                        <div class="section-title">Input Data</div>
                        <div class="row g-3">
                            <div class="col-6">
                                <button onclick="handleCategoryClick('sakit')" class="cat-btn">
                                    <div class="cat-icon-box theme-sakit"><i class="fas fa-procedures"></i></div>
                                    <span class="fw-bold small">Sakit</span>
                                </button>
                            </div>
                            @if($role !== 'kesehatan')
                                <div class="col-6">
                                    <button onclick="handleCategoryClick('izin_keluar')" class="cat-btn">
                                        <div class="cat-icon-box theme-izin-keluar"><i class="fas fa-sign-out-alt"></i></div>
                                        <span class="fw-bold small">Izin Keluar</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="handleCategoryClick('izin_pulang')" class="cat-btn">
                                        <div class="cat-icon-box theme-izin-pulang"><i class="fas fa-home"></i></div>
                                        <span class="fw-bold small">Izin Pulang</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="handleCategoryClick('sambangan')" class="cat-btn">
                                        <div class="cat-icon-box theme-sambangan"><i class="fas fa-users"></i></div>
                                        <span class="fw-bold small">Sambangan</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="handleCategoryClick('pelanggaran')" class="cat-btn">
                                        <div class="cat-icon-box theme-pelanggaran"><i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <span class="fw-bold small">Pelanggaran</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="handleCategoryClick('paket')" class="cat-btn">
                                        <div class="cat-icon-box theme-paket"><i class="fas fa-box-open"></i></div>
                                        <span class="fw-bold small">Paket</span>
                                    </button>
                                </div>
                            @endif
                            @if($role === 'admin' || $role === 'guru')
                                <div class="col-6">
                                    <button onclick="handleCategoryClick('hafalan')" class="cat-btn">
                                        <div class="cat-icon-box theme-hafalan"><i class="fas fa-quran"></i></div>
                                        <span class="fw-bold small">Hafalan</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TABEL RIWAYAT -->
        <div class="col-lg-8">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 w-100">
                        <!-- Title + Category Filter -->
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center flex-shrink-0">
                                <i class="fas fa-history me-2 text-primary-custom"></i>
                                <span class="d-none d-lg-inline">RIWAYAT AKTIVITAS</span>
                                <span class="d-lg-none">RIWAYAT</span>
                            </h6>
                            <select id="filter_kategori" class="custom-select-filter" style="min-width: 150px;">
                                <option value="all">SEMUA KATEGORI</option>
                                <option value="sakit">SAKIT</option>
                                @if($role !== 'kesehatan')
                                    <option value="izin_keluar">IZIN KELUAR</option>
                                    <option value="izin_pulang">IZIN PULANG</option>
                                    <option value="sambangan">SAMBANGAN</option>
                                    <option value="paket">PAKET</option>
                                    <option value="pelanggaran">PELANGGARAN</option>
                                @endif
                                @if($role === 'admin' || $role === 'guru')
                                    <option value="hafalan">HAFALAN</option>
                                @endif
                            </select>
                        </div>
                        <!-- Date Filters + Search -->
                        <div class="d-flex align-items-center gap-2 flex-nowrap">
                            <input type="date" id="filter_tanggal_dari" class="form-control form-control-sm"
                                style="width: 130px;">
                            <span class="text-muted">-</span>
                            <input type="date" id="filter_tanggal_sampai" class="form-control form-control-sm"
                                style="width: 130px;">
                            <input type="text" id="filter_search" class="form-control form-control-sm" placeholder="Cari..."
                                style="width: 100px;">
                            <button class="btn btn-light border btn-sm px-3" onclick="refreshTable()" id="btn-refresh"><i
                                    class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div id="bulk-actions"
                    class="bg-success bg-opacity-10 px-4 py-2 d-none d-flex justify-content-between align-items-center border-bottom border-success border-opacity-25">
                    <span class="small fw-bold text-success"><i class="fas fa-check-circle me-1"></i> <span
                            id="selected-count">0</span> data terpilih</span>
                    <div>
                        <button id="btn-bulk-wa" class="btn btn-success btn-sm border-0 fw-bold shadow-sm"><i
                                class="fab fa-whatsapp me-1"></i> WA Massal</button>
                        <button id="btn-bulk-report" class="btn btn-primary btn-sm border-0 ms-2 fw-bold shadow-sm"
                            disabled title="Pilih item dengan kategori yang sama"><i class="fas fa-file-alt me-1"></i>
                            Laporan</button>
                        @if($isAdmin)
                            <button id="btn-bulk-delete" class="btn btn-danger btn-sm border-0 ms-2 fw-bold shadow-sm"><i
                                    class="fas fa-trash-alt me-1"></i> Hapus</button>
                        @endif
                    </div>
                </div>

                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover w-100 mb-0" id="table-aktivitas">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL INPUT DATA -->
    <div class="modal fade" id="modalInput" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formAktivitas" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="log_id" id="modal_log_id">
                    <input type="hidden" name="siswa_id" id="modal_siswa_id">
                    <input type="hidden" name="kategori" id="modal_kategori">

                    <div class="modal-header text-white border-0" style="background-color: var(--primary-color);">
                        <h6 class="modal-title fw-bold" id="modalTitle">INPUT DATA</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body bg-light bg-opacity-50">
                        <div class="row g-3">
                            <div class="col-md-4" id="col_tanggal_mulai">
                                <label class="form-label-custom" id="lbl_tanggal_mulai">TANGGAL MULAI</label>
                                <input type="datetime-local" name="tanggal" id="input_tanggal"
                                    class="form-control-custom w-100" required>
                            </div>
                            <div class="col-md-4 d-none" id="group_batas_waktu">
                                <label class="form-label-custom">BATAS WAKTU <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="batas_waktu" id="input_batas_waktu"
                                    class="form-control-custom w-100">
                            </div>
                            <div class="col-md-4 d-none" id="group_tanggal_selesai">
                                <label class="form-label-custom" id="lbl_tanggal_selesai">TANGGAL SELESAI</label>
                                <input type="datetime-local" name="tanggal_selesai" id="input_tanggal_selesai"
                                    class="form-control-custom w-100">
                                <small class="text-muted">Opsional</small>
                            </div>
                            <div class="col-12" id="group_judul">
                                <label class="form-label-custom" id="lbl_judul">JUDUL</label>
                                <input type="text" name="judul" id="input_judul" class="form-control-custom w-100"
                                    placeholder="...">
                            </div>
                            <div class="col-12 d-none" id="group_sambangan">
                                <label class="form-label-custom">STATUS PENJENGUK</label>
                                <select name="status_sambangan" id="select_status_sambangan"
                                    class="form-select form-control-custom w-100">
                                    <option value="">-- Pilih --</option>
                                    <option value="Keluarga">Keluarga Inti</option>
                                    <option value="Kerabat">Kerabat</option>
                                    <option value="Teman">Teman</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12 d-none" id="group_status_sakit">
                                <label class="form-label-custom">STATUS PERIKSA</label>
                                <select name="status_kegiatan" id="select_status_kegiatan"
                                    class="form-select form-control-custom w-100">
                                    <option value="Belum Diperiksa">Belum Diperiksa</option>
                                    <option value="Sudah Diperiksa">Sudah Diperiksa</option>
                                </select>
                            </div>
                            <div class="col-12 d-none" id="group_status_paket">
                                <label class="form-label-custom">STATUS PAKET <span class="text-danger">*</span></label>
                                <select name="status_paket" id="select_status_paket"
                                    class="form-select form-control-custom w-100">
                                    <option value="Belum Diterima">Belum Diterima</option>
                                    <option value="Sudah Diterima">Sudah Diterima</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">KETERANGAN</label>
                                <textarea name="keterangan" id="textarea_keterangan" class="form-control-custom w-100"
                                    rows="3" placeholder="Tambahkan detail..."></textarea>
                            </div>
                            <div class="col-12" id="group_foto">
                                <div class="row g-3">
                                    <div class="col-md-6" id="col_foto_1">
                                        <label class="form-label-custom" id="lbl_foto_1">FOTO BUKTI <span
                                                class="text-muted fw-normal">(Opsional)</span></label>
                                        <div class="photo-upload-wrapper" id="wrapper_foto_1">
                                            <input type="file" name="foto_dokumen_1" id="input_foto_1" class="d-none"
                                                accept="image/*"
                                                onchange="handlePhotoSelect(this, 'preview_foto_1', 'wrapper_foto_1')">
                                            <input type="file" id="camera_foto_1" class="d-none" accept="image/*"
                                                capture="environment"
                                                onchange="handlePhotoSelect(this, 'preview_foto_1', 'wrapper_foto_1', 'input_foto_1')">
                                            <div class="photo-upload-buttons" id="buttons_foto_1">
                                                <button type="button" class="btn-photo-upload btn-camera"
                                                    onclick="document.getElementById('camera_foto_1').click()">
                                                    <i class="fas fa-camera"></i> Ambil Foto
                                                </button>
                                                <button type="button" class="btn-photo-upload btn-file"
                                                    onclick="document.getElementById('input_foto_1').click()">
                                                    <i class="fas fa-folder-open"></i> Pilih File
                                                </button>
                                            </div>
                                            <div class="photo-preview-container d-none" id="container_foto_1">
                                                <img id="preview_foto_1" alt="Preview">
                                                <button type="button" class="btn-remove-photo"
                                                    onclick="removePhoto('input_foto_1', 'preview_foto_1', 'wrapper_foto_1', 'container_foto_1', 'buttons_foto_1')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL WA MASSAL PERSONAL -->
    <div class="modal fade" id="modalBulkWa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white border-0" style="background-color: #25D366;">
                    <h6 class="modal-title fw-bold"><i class="fab fa-whatsapp me-2"></i>WA MASSAL PERSONAL</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-success bg-success bg-opacity-10 border-0 p-3 mb-3 small text-success rounded-3">
                        <i class="fas fa-info-circle me-1"></i> Kirim pemberitahuan personal ke <strong><span id="bulk_wa_count">0</span> wali</strong>
                        <br><small class="text-muted">Setiap wali menerima detail aktivitas anaknya masing-masing</small>
                    </div>

                    <!-- List Penerima -->
                    <div class="mb-3">
                        <label class="form-label-custom mb-2">DAFTAR PENERIMA</label>
                        <div id="bulk_wa_list" class="border rounded-3 p-2" style="max-height: 180px; overflow-y: auto; background: #f8fafc;">
                            <!-- Filled by JS -->
                        </div>
                    </div>

                    <!-- Preview Format -->
                    <div class="mb-3">
                        <label class="form-label-custom mb-2">CONTOH FORMAT PESAN</label>
                        <div id="bulk_wa_preview" class="border rounded-3 p-3 small" style="background: #f0fdf4; font-family: monospace; white-space: pre-wrap;">
                            <!-- Filled by JS -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" id="btn_send_bulk_wa"><i class="fab fa-whatsapp me-1"></i> KIRIM SEKARANG</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL KIRIM WA SINGLE -->
    <div class="modal fade" id="modalSendWa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white border-0" style="background-color: #10b981;">
                    <h6 class="modal-title fw-bold"><i class="fab fa-whatsapp me-2"></i>KIRIM WA KE WALI</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="single_wa_phone">
                    <input type="hidden" id="single_wa_image">
                    <div class="mb-3">
                        <label class="form-label-custom">NOMOR TUJUAN</label>
                        <div id="single_wa_phone_display" class="fw-bold text-success"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">ISI PESAN</label>
                        <textarea id="single_wa_message" class="form-control form-control-custom w-100" rows="8"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" id="btn_send_single_wa"><i class="fas fa-paper-plane me-1"></i> KIRIM SEKARANG</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REPORT PREVIEW -->
    <div class="modal fade" id="modalReport" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white border-0" style="background-color: #3b82f6;">
                    <h6 class="modal-title fw-bold"><i class="fas fa-file-alt me-2"></i>PREVIEW LAPORAN</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info bg-info bg-opacity-10 border-0 p-3 mb-3 small text-info rounded-3">
                        <i class="fas fa-info-circle me-1"></i> Laporan dari <strong><span id="report_count">0</span> data</strong> dengan kategori <strong><span id="report_category">-</span></strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">TEKS LAPORAN</label>
                        <textarea id="report_text" class="form-control form-control-custom w-100" rows="12" readonly style="font-family: monospace; font-size: 0.85rem; white-space: pre-wrap;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" id="btn_copy_report"><i class="fas fa-copy me-1"></i> COPY TEKS</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        // Laravel CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const role = '{{ $role }}';
        const isAdmin = (role === 'admin');
        let table;

        // Column Definitions
        const colDefs = {
            'default': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', width: '5%', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Tanggal', data: 'tanggal', render: formatTgl },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Kategori', data: 'kategori', render: (d) => `<span class="badge bg-light text-dark border">${d.replace('_', ' ')}</span>` },
                { title: 'Judul/Isi', data: 'judul' },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'sakit': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Tgl Sakit', data: 'tanggal', render: formatTgl },
                { title: 'Tgl Sembuh', data: 'tanggal_selesai', render: (d) => d ? formatTgl(d) : '<span class="badge bg-danger rounded-pill px-3">Belum Sembuh</span>' },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Diagnosa', data: 'judul' },
                {
                    title: 'Status', data: 'status_kegiatan', render: (d) => {
                        let status = d || 'Belum Diperiksa';
                        let color = status === 'Sudah Diperiksa' ? 'bg-success' : 'bg-warning';
                        return `<span class="badge ${color} rounded-pill">${status}</span>`;
                    }
                },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'izin_keluar': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Waktu Pergi', data: 'tanggal', render: formatTgl },
                { title: 'Waktu Kembali', data: 'tanggal_selesai', render: (d) => d ? formatTgl(d) : '<span class="badge bg-warning rounded-pill px-3">Belum Kembali</span>' },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Keperluan', data: 'judul' },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'izin_pulang': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Waktu Pergi', data: 'tanggal', render: formatTgl },
                { title: 'Waktu Kembali', data: 'tanggal_selesai', render: (d) => d ? formatTgl(d) : '<span class="badge bg-warning rounded-pill px-3">Belum Kembali</span>' },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Alasan', data: 'judul' },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'sambangan': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Waktu', data: 'tanggal', render: formatTgl },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Penjenguk', data: 'judul' },
                { title: 'Hubungan', data: 'status_sambangan', render: (d) => `<span class="badge bg-info">${d || '-'}</span>` },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'pelanggaran': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Tanggal', data: 'tanggal', render: formatTgl },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Jenis', data: 'judul' },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'hafalan': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Tanggal', data: 'tanggal', render: formatTgl },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Kitab/Surat', data: 'judul' },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ],
            'paket': [
                { title: '<input type="checkbox" id="select-all">', data: 'id', orderable: false, render: (d, t, r) => `<input type="checkbox" class="form-check-input row-checkbox" value="${d}" data-phone="${r.no_wa_wali || ''}">` },
                { title: 'Tgl Tiba', data: 'tanggal', render: formatTgl },
                { title: 'Tgl Terima', data: 'tanggal_selesai', render: (d) => d ? formatTgl(d) : '<span class="badge bg-warning rounded-pill">Belum Diterima</span>' },
                { title: 'Siswa', data: 'nama_lengkap', render: (d, t, r) => renderSiswa(r) },
                { title: 'Isi Paket', data: 'judul' },
                { title: 'Foto', data: 'foto_dokumen_1', render: (d, t, r) => renderFotoPaket(d, r) },
                { title: 'Aksi', data: 'id', render: (d, t, r) => renderAksi(d, r) }
            ]
        };

        // Helper Functions
        function formatTgl(data) {
            if (!data) return '-';
            let d = new Date(data);
            return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
        }

        function renderSiswa(r) {
            return `<div class="fw-bold text-dark">${r.nama_lengkap}</div><small class="text-muted">${r.nomor_induk || '-'}</small>`;
        }

        function renderFotoPaket(d, r) {
            let html = '';
            if (d) html += `<a href="/storage/${d}" target="_blank" class="btn btn-sm btn-light border text-primary me-1" title="Foto Paket"><i class="fas fa-box-open"></i></a>`;
            if (r.foto_dokumen_2) html += `<a href="/storage/${r.foto_dokumen_2}" target="_blank" class="btn btn-sm btn-light border text-success" title="Foto Penerima"><i class="fas fa-user-check"></i></a>`;
            return html || '-';
        }

        function renderAksi(id, row) {
            let siswaName = (row.nama_lengkap || '').replace(/'/g, "");
            let btnEdit = `<button class="btn btn-sm btn-outline-warning btn-edit me-1" data-id="${id}" title="Edit"><i class="fas fa-pencil-alt"></i></button>`;
            let btnWa = `<button class="btn btn-sm btn-outline-success btn-wa-single me-1" data-id="${id}" title="Kirim WA ke Wali"><i class="fab fa-whatsapp"></i></button>`;

            if (isAdmin) {
                let btnDelete = `<button class="btn btn-sm btn-outline-danger btn-delete-single" data-id="${id}" data-name="${siswaName}" title="Hapus"><i class="fas fa-trash-alt"></i></button>`;
                return `<div class="d-flex">${btnEdit} ${btnWa} ${btnDelete}</div>`;
            }
            return `<div class="d-flex">${btnEdit} ${btnWa}</div>`;
        }

        // Initialize DataTable
        window.refreshTable = function () {
            let cat = $('#filter_kategori').val();
            let defs = colDefs[cat] || colDefs['default'];

            if ($.fn.DataTable.isDataTable('#table-aktivitas')) {
                $('#table-aktivitas').DataTable().destroy();
                $('#table-aktivitas').empty();
            }

            table = $('#table-aktivitas').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 10,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [[1, 'desc']],
                dom: 'rt<"bottom"p><"clear">',
                ajax: {
                    url: "{{ route('api.aktivitas.data') }}",
                    type: "POST",
                    data: function (d) {
                        d.kategori = (cat === 'all') ? '' : cat;
                        d.search_keyword = $('#filter_search').val();
                        d.tanggal_dari = $('#filter_tanggal_dari').val();
                        d.tanggal_sampai = $('#filter_tanggal_sampai').val();
                    },
                    beforeSend: function () {
                        $('#btn-refresh i').addClass('fa-spin');
                    },
                    complete: function () {
                        $('#btn-refresh i').removeClass('fa-spin');
                    },
                    error: function (xhr, error, thrown) {
                        console.error('AJAX Error:', error, thrown);
                        Swal.fire('Error', 'Gagal memuat data. Silakan refresh halaman.', 'error');
                    }
                },
                columns: defs,
                drawCallback: function () {
                    $('#select-all').prop('checked', false);
                    toggleBulkActions();
                }
            });
        };

        // Category Click Handler
        window.handleCategoryClick = function (cat) {
            $('#filter_kategori').val(cat);
            refreshTable();

            let sid = $('#selected_siswa_id').val();
            if (sid) {
                bukaModal(cat);
            } else {
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 })
                    .fire({ icon: 'info', title: `Menampilkan data: ${cat.toUpperCase().replace('_', ' ')}` });
            }
        };

        // Initialize
        $(document).ready(function () {
            refreshTable();
            $('#filter_kategori').on('change', refreshTable);
            $('#filter_tanggal_dari, #filter_tanggal_sampai').on('change', function () { table.draw(); });

            let searchTimer;
            $('#filter_search').on('keyup', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => table.draw(), 400);
            });
        });

        // Bulk Actions
        $(document).on('click', '#select-all', function () {
            $('.row-checkbox').prop('checked', this.checked);
            toggleBulkActions();
        });
        $(document).on('click', '.row-checkbox', toggleBulkActions);

        function toggleBulkActions() {
            let count = $('.row-checkbox:checked').length;
            $('#selected-count').text(count);
            if (count > 0) {
                $('#bulk-actions').removeClass('d-none');
            } else {
                $('#bulk-actions').addClass('d-none');
            }
        }

        // Search Siswa
        $('#input_cari').on('keyup', function () {
            let kw = $(this).val();
            if (kw.length < 3) { $('#hasil_autocomplete').addClass('d-none'); return; }
            $.get("{{ route('api.santri.search') }}", { q: kw }, function (res) {
                let html = '';
                res.forEach(s => {
                    html += `<a href="#" class="list-group-item list-group-item-action search-result-item" 
                                        data-id="${s.id}" data-nama="${s.nama_lengkap}" data-nis="${s.nisn}" 
                                        data-kelas="${s.kelas}" data-alamat="${s.alamat || ''}" data-phone="${s.no_wa_wali || ''}">
                                        <div class="fw-bold">${s.nama_lengkap}</div>
                                        <div class="small text-muted">${s.kelas} | ${s.nisn}</div>
                                    </a>`;
                });
                $('#hasil_autocomplete').html(html).removeClass('d-none');
            });
        });

        $(document).on('click', '.search-result-item', function (e) {
            e.preventDefault();
            let d = $(this).data();
            pilihSiswa(d.id, d.nama, d.nis, d.kelas, d.alamat, d.phone);
        });

        window.pilihSiswa = function (id, nama, nis, kelas, alamat, phone) {
            $('#selected_siswa_id').val(id);
            $('#selected_siswa_phone').val(phone);
            $('#lbl_nama').text(nama);
            $('#lbl_nis').text(nis);
            $('#lbl_kelas').text(kelas);
            $('#lbl_alamat').text(alamat);
            $('#empty_card').addClass('d-none');
            $('#card_siswa').removeClass('d-none');
            $('#hasil_autocomplete').addClass('d-none');
            $('#input_cari').val('');
        };

        // QR Scanner
        let html5QrCode = null;
        $('#btn_buka_kamera').click(function () {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop();
            }
            
            $('#area_kamera').removeClass('d-none');
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    html5QrCode.stop().then(() => {
                        $('#area_kamera').addClass('d-none');
                        // QR code contains student data - usually NISN or ID
                        $.get("{{ route('api.santri.search') }}", { q: decodedText }, function (res) {
                            if (res.length > 0) {
                                let s = res[0];
                                pilihSiswa(s.id, s.nama_lengkap, s.nisn, s.kelas, s.alamat || '', s.no_wa_wali || '');
                                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 })
                                    .fire({ icon: 'success', title: `Siswa ditemukan: ${s.nama_lengkap}` });
                            } else {
                                Swal.fire('Tidak Ditemukan', 'Data siswa dengan kode tersebut tidak ditemukan', 'warning');
                            }
                        });
                    });
                },
                (error) => { /* ignore scan errors */ }
            ).catch(err => {
                console.error('QR Scanner error:', err);
                Swal.fire('Error', 'Tidak dapat mengakses kamera', 'error');
                $('#area_kamera').addClass('d-none');
            });
        });

        $('#btn_tutup_kamera').click(function () {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop();
            }
            $('#area_kamera').addClass('d-none');
        });

        $('#btn_reset').click(function () {
            $('#selected_siswa_id').val('');
            $('#empty_card').removeClass('d-none');
            $('#card_siswa').addClass('d-none');
            $('#input_cari').val('');
        });

        // Modal Input
        window.bukaModal = function (cat, editData = null) {
            if (!editData && !$('#selected_siswa_id').val()) {
                Swal.fire('Pilih Siswa', 'Silakan pilih siswa dulu untuk input data.', 'info');
                return;
            }

            $('#formAktivitas')[0].reset();
            $('#group_sambangan, #group_tanggal_selesai, #group_status_sakit, #group_batas_waktu, #group_status_paket').addClass('d-none');

            let title = "INPUT DATA", lbl = "JUDUL";
            if (cat == 'sakit') {
                title = "SAKIT"; lbl = "DIAGNOSA";
                $('#lbl_tanggal_mulai').text('TANGGAL SAKIT');
                $('#group_tanggal_selesai').removeClass('d-none');
                $('#lbl_tanggal_selesai').text('TANGGAL SEMBUH');
                $('#group_status_sakit').removeClass('d-none');
            }
            else if (cat == 'izin_keluar') {
                title = "IZIN KELUAR"; lbl = "KEPERLUAN";
                $('#lbl_tanggal_mulai').text('TANGGAL PERGI');
                $('#group_batas_waktu').removeClass('d-none');
                $('#group_tanggal_selesai').removeClass('d-none');
                $('#lbl_tanggal_selesai').text('TANGGAL KEMBALI');
            }
            else if (cat == 'izin_pulang') {
                title = "IZIN PULANG"; lbl = "ALASAN";
                $('#group_batas_waktu').removeClass('d-none');
                $('#group_tanggal_selesai').removeClass('d-none');
                $('#lbl_tanggal_selesai').text('TGL KEMBALI');
            }
            else if (cat == 'sambangan') {
                title = "SAMBANGAN"; lbl = "NAMA PENJENGUK";
                $('#group_sambangan').removeClass('d-none');
            }
            else if (cat == 'paket') {
                title = "PAKET"; lbl = "ISI PAKET";
                $('#group_status_paket').removeClass('d-none');
                $('#group_tanggal_selesai').removeClass('d-none');
                $('#lbl_tanggal_selesai').text('TGL TERIMA');
            }
            else if (cat == 'pelanggaran') {
                title = "PELANGGARAN"; lbl = "JENIS PELANGGARAN";
            }
            else if (cat == 'hafalan') {
                title = "HAFALAN"; lbl = "NAMA KITAB/SURAT";
            }

            $('#modalTitle').text(editData ? `EDIT DATA ${title}` : title);
            $('#lbl_judul').text(lbl);
            $('#modal_kategori').val(cat);
            $('#modal_siswa_id').val($('#selected_siswa_id').val());

            // Set current datetime as default
            let now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#input_tanggal').val(now.toISOString().slice(0, 16));

            new bootstrap.Modal(document.getElementById('modalInput')).show();
        };

        // Form Submit
        $('#formAktivitas').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: "{{ route('api.aktivitas.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire('Sukses', res.message, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('modalInput')).hide();
                        refreshTable();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function (xhr) {
                    let msg = 'Terjadi kesalahan';
                    try {
                        let res = JSON.parse(xhr.responseText);
                        if (res.message) msg = res.message;
                    } catch (e) { }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });

        // Photo handling
        function handlePhotoSelect(input, previewId, wrapperId, targetInputId = null) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + previewId).attr('src', e.target.result);
                    $('#container_' + previewId.replace('preview_', '')).removeClass('d-none');
                    $('#buttons_' + previewId.replace('preview_', '')).addClass('d-none');
                    $('#' + wrapperId).addClass('has-preview');

                    if (targetInputId) {
                        let dt = new DataTransfer();
                        dt.items.add(input.files[0]);
                        document.getElementById(targetInputId).files = dt.files;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePhoto(inputId, previewId, wrapperId, containerId, buttonsId) {
            $('#' + inputId).val('');
            $('#' + previewId).attr('src', '');
            $('#' + containerId).addClass('d-none');
            $('#' + buttonsId).removeClass('d-none');
            $('#' + wrapperId).removeClass('has-preview');
        }

        // Delete single
        $(document).on('click', '.btn-delete-single', function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Data?',
                text: `Data aktivitas "${name}" akan dihapus`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((r) => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `/api/aktivitas/${id}`,
                        type: 'DELETE',
                        success: function () {
                            refreshTable();
                            Swal.fire('Sukses', 'Data berhasil dihapus', 'success');
                        },
                        error: function () {
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        }
                    });
                }
            });
        });

        // Bulk Delete
        $('#btn-bulk-delete').click(function () {
            let ids = [];
            $('.row-checkbox:checked').each(function () { ids.push($(this).val()); });
            if (ids.length === 0) return;

            Swal.fire({
                title: 'Hapus Data?',
                text: `${ids.length} data terpilih akan dihapus`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((r) => {
                if (r.isConfirmed) {
                    $.post("{{ route('api.aktivitas.bulk-delete') }}", { ids: ids })
                        .done(function () {
                            refreshTable();
                            Swal.fire('Sukses', 'Data berhasil dihapus', 'success');
                        })
                        .fail(function () {
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        });
                }
            });
        });

        // Single WA - Send to wali
        $(document).on('click', '.btn-wa-single', function () {
            let btn = $(this);
            let rowData = table.row(btn.closest('tr')).data();

            if (!rowData) {
                Swal.fire('Error', 'Data tidak ditemukan', 'error');
                return;
            }

            if (!rowData.no_wa_wali || rowData.no_wa_wali === '-') {
                Swal.fire('Perhatian', 'Nomor WA wali tidak tersedia', 'warning');
                return;
            }

            let message = generatePersonalMessage(rowData);

            Swal.fire({
                title: 'Kirim WA ke Wali?',
                html: `Kirim pemberitahuan ke <b>${rowData.no_wa_wali}</b><br><small class="text-muted">Wali dari ${rowData.nama_lengkap}</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#25D366',
                confirmButtonText: '<i class="fab fa-whatsapp"></i> Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    $.post("{{ route('api.kirim-wa') }}", {
                        phone: rowData.no_wa_wali,
                        message: message,
                        image: rowData.kategori === 'paket' ? rowData.foto_dokumen_1 : null
                    }).done(function (res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pesan berhasil dikirim ke wali',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
                        }
                    }).fail(function (xhr) {
                        let errorMsg = 'Gagal mengirim pesan';
                        try {
                            let res = JSON.parse(xhr.responseText);
                            if (res.message) errorMsg = res.message;
                        } catch (e) { }
                        Swal.fire('Error', errorMsg, 'error');
                    });
                }
            });
        });

        // Bulk WA
        $('#btn-bulk-wa').click(function () {
            let dataList = [];
            $('.row-checkbox:checked').each(function () {
                let rowData = table.row($(this).closest('tr')).data();
                if (rowData && rowData.no_wa_wali && rowData.no_wa_wali !== '-') {
                    dataList.push(rowData);
                }
            });

            if (dataList.length === 0) {
                Swal.fire('Perhatian', 'Tidak ada nomor wali yang tersedia', 'warning');
                return;
            }

            Swal.fire({
                title: 'Kirim WA Massal?',
                html: `Kirim pemberitahuan ke <b>${dataList.length}</b> wali santri`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#25D366',
                confirmButtonText: '<i class="fab fa-whatsapp"></i> Kirim Semua',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Pesan...',
                        html: `Mengirim ke <b>0</b> dari <b>${dataList.length}</b> wali`,
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    let sent = 0, failed = 0;
                    let promises = dataList.map((item, idx) => {
                        return new Promise((resolve) => {
                            setTimeout(() => {
                                let message = generatePersonalMessage(item);
                                $.post("{{ route('api.kirim-wa') }}", {
                                    phone: item.no_wa_wali,
                                    message: message,
                                    image: item.kategori === 'paket' ? item.foto_dokumen_1 : null
                                }).done(function (res) {
                                    if (res.status === 'success') sent++;
                                    else failed++;
                                }).fail(function () {
                                    failed++;
                                }).always(function () {
                                    Swal.update({
                                        html: `Mengirim ke <b>${sent + failed}</b> dari <b>${dataList.length}</b> wali`
                                    });
                                    resolve();
                                });
                            }, idx * 500);
                        });
                    });

                    Promise.all(promises).then(() => {
                        Swal.fire({
                            icon: failed === 0 ? 'success' : 'warning',
                            title: 'Selesai!',
                            html: `Berhasil mengirim ke <b>${sent}</b> wali` + (failed > 0 ? `<br>Gagal: <b>${failed}</b>` : ''),
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });

        // Generate personal message for WA
        function generatePersonalMessage(item) {
            let hour = new Date().getHours();
            let greeting = 'Selamat pagi';
            if (hour >= 11 && hour < 15) greeting = 'Selamat siang';
            else if (hour >= 15 && hour < 18) greeting = 'Selamat sore';
            else if (hour >= 18 || hour < 5) greeting = 'Selamat malam';

            let kategori = item.kategori;
            let nama = item.nama_lengkap || '-';

            let kategoriLabels = {
                'sakit': 'Pemberitahuan Santri Sakit',
                'izin_keluar': 'Izin Keluar',
                'izin_pulang': 'Izin Pulang',
                'sambangan': 'Pemberitahuan Sambangan',
                'pelanggaran': 'Pemberitahuan Pelanggaran',
                'paket': 'Pemberitahuan Paket Masuk',
                'hafalan': 'Pemberitahuan Hafalan'
            };

            let lines = [];
            lines.push(`${greeting}, Bapak/Ibu Wali dari *${nama}*`);
            lines.push('');
            lines.push(`Berikut informasi ${kategoriLabels[kategori] || 'Aktivitas'} putra/putri Anda:`);
            lines.push('');
            lines.push(`Tanggal: ${formatDateTimeWA(item.tanggal)}`);
            lines.push(`Judul: ${item.judul || '-'}`);
            lines.push(`Keterangan: ${item.keterangan || '-'}`);
            lines.push('');
            lines.push('Terima kasih.');
            lines.push('Mambaul Huda');

            return lines.join('\n');
        }

        function formatDateTimeWA(dateStr) {
            if (!dateStr) return '-';
            let d = new Date(dateStr);
            if (isNaN(d.getTime())) return '-';
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' +
                d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
        }

        // Edit button handler
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.get(`/api/aktivitas/${id}/edit`).done(function (res) {
                Swal.close();
                if (res.status === 'success') {
                    let data = res.data;

                    // Set siswa info
                    pilihSiswa(data.siswa_id, data.nama_lengkap, '-', data.kelas, '-', '-');

                    // Open modal with edit data
                    bukaModal(data.kategori, data);

                    // Fill form fields
                    $('#modal_log_id').val(data.id);
                    $('#input_tanggal').val(data.tanggal);
                    $('#input_tanggal_selesai').val(data.tanggal_selesai);
                    $('#input_batas_waktu').val(data.batas_waktu);
                    $('#input_judul').val(data.judul);
                    $('#textarea_keterangan').val(data.keterangan);
                    $('#select_status_sambangan').val(data.status_sambangan);
                    $('#select_status_kegiatan').val(data.status_kegiatan);
                    $('#select_status_paket').val(data.status_kegiatan);
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }).fail(function () {
                Swal.fire('Error', 'Gagal memuat data', 'error');
            });
        });

        // ========================
        // WA SINGLE WITH MODAL
        // ========================
        let currentWaRowData = null;
        $(document).on('click', '.btn-wa-single', function () {
            let btn = $(this);
            currentWaRowData = table.row(btn.closest('tr')).data();

            if (!currentWaRowData) {
                Swal.fire('Error', 'Data tidak ditemukan', 'error');
                return;
            }

            if (!currentWaRowData.no_wa_wali || currentWaRowData.no_wa_wali === '-') {
                Swal.fire('Perhatian', 'Nomor WA wali tidak tersedia', 'warning');
                return;
            }

            // Populate modal
            $('#single_wa_phone').val(currentWaRowData.no_wa_wali);
            $('#single_wa_phone_display').text(currentWaRowData.no_wa_wali);
            $('#single_wa_image').val(currentWaRowData.kategori === 'paket' ? currentWaRowData.foto_dokumen_1 : '');
            $('#single_wa_message').val(generatePersonalMessage(currentWaRowData));

            new bootstrap.Modal(document.getElementById('modalSendWa')).show();
        });

        $('#btn_send_single_wa').click(function () {
            let phone = $('#single_wa_phone').val();
            let message = $('#single_wa_message').val();
            let image = $('#single_wa_image').val();

            if (!phone || !message) return;

            bootstrap.Modal.getInstance(document.getElementById('modalSendWa')).hide();

            Swal.fire({
                title: 'Mengirim...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.post("{{ route('api.kirim-wa') }}", { phone: phone, message: message, image: image || null })
                .done(function (res) {
                    if (res.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Pesan berhasil dikirim', timer: 2000, showConfirmButton: false });
                    } else {
                        Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .fail(function () { Swal.fire('Error', 'Gagal mengirim pesan', 'error'); });
        });

        // ========================
        // BULK WA WITH MODAL
        // ========================
        let bulkWaDataList = [];
        $('#btn-bulk-wa').click(function () {
            bulkWaDataList = [];
            $('.row-checkbox:checked').each(function () {
                let rowData = table.row($(this).closest('tr')).data();
                if (rowData && rowData.no_wa_wali && rowData.no_wa_wali !== '-') {
                    bulkWaDataList.push(rowData);
                }
            });

            if (bulkWaDataList.length === 0) {
                Swal.fire('Perhatian', 'Tidak ada nomor wali yang tersedia', 'warning');
                return;
            }

            // Populate modal
            $('#bulk_wa_count').text(bulkWaDataList.length);

            let listHtml = '';
            bulkWaDataList.forEach(item => {
                listHtml += `<div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                    <div><strong>${item.nama_lengkap}</strong><br><small class="text-muted">${item.kategori.replace('_', ' ')}</small></div>
                    <span class="badge bg-success">${item.no_wa_wali}</span>
                </div>`;
            });
            $('#bulk_wa_list').html(listHtml);

            // Preview first message
            if (bulkWaDataList.length > 0) {
                $('#bulk_wa_preview').text(generatePersonalMessage(bulkWaDataList[0]));
            }

            new bootstrap.Modal(document.getElementById('modalBulkWa')).show();
        });

        $('#btn_send_bulk_wa').click(function () {
            if (bulkWaDataList.length === 0) return;

            bootstrap.Modal.getInstance(document.getElementById('modalBulkWa')).hide();

            Swal.fire({
                title: 'Mengirim Pesan...',
                html: `Mengirim ke <b>0</b> dari <b>${bulkWaDataList.length}</b> wali`,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            let sent = 0, failed = 0;
            let promises = bulkWaDataList.map((item, idx) => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        let message = generatePersonalMessage(item);
                        $.post("{{ route('api.kirim-wa') }}", {
                            phone: item.no_wa_wali,
                            message: message,
                            image: item.kategori === 'paket' ? item.foto_dokumen_1 : null
                        }).done(function (res) {
                            if (res.status === 'success') sent++;
                            else failed++;
                        }).fail(function () {
                            failed++;
                        }).always(function () {
                            Swal.update({
                                html: `Mengirim ke <b>${sent + failed}</b> dari <b>${bulkWaDataList.length}</b> wali`
                            });
                            resolve();
                        });
                    }, idx * 500);
                });
            });

            Promise.all(promises).then(() => {
                Swal.fire({
                    icon: failed === 0 ? 'success' : 'warning',
                    title: 'Selesai!',
                    html: `Berhasil mengirim ke <b>${sent}</b> wali` + (failed > 0 ? `<br>Gagal: <b>${failed}</b>` : ''),
                    confirmButtonText: 'OK'
                });
            });
        });

        // ========================
        // BULK REPORT
        // ========================
        function updateBulkReportButton() {
            let categories = new Set();
            $('.row-checkbox:checked').each(function () {
                let rowData = table.row($(this).closest('tr')).data();
                if (rowData) categories.add(rowData.kategori);
            });

            if (categories.size === 1) {
                $('#btn-bulk-report').prop('disabled', false).attr('title', 'Generate laporan');
            } else {
                $('#btn-bulk-report').prop('disabled', true).attr('title', 'Pilih item dengan kategori yang sama');
            }
        }

        $(document).on('click', '.row-checkbox, #select-all', function () {
            updateBulkReportButton();
        });

        $('#btn-bulk-report').click(function () {
            let dataList = [];
            let kategori = '';
            $('.row-checkbox:checked').each(function () {
                let rowData = table.row($(this).closest('tr')).data();
                if (rowData) {
                    if (!kategori) kategori = rowData.kategori;
                    dataList.push(rowData);
                }
            });

            if (dataList.length === 0) return;

            // Generate report text
            let reportText = generateReportText(kategori, dataList);

            // Populate modal
            $('#report_count').text(dataList.length);
            $('#report_category').text(kategori.replace('_', ' ').toUpperCase());
            $('#report_text').val(reportText);

            new bootstrap.Modal(document.getElementById('modalReport')).show();
        });

        function generateReportText(kategori, items) {
            let greeting = getGreeting();
            let lines = [];
            lines.push(`${greeting},`);
            lines.push('');
            lines.push(`Berikut laporan ${kategori.replace('_', ' ').toUpperCase()} tanggal ${new Date().toLocaleDateString('id-ID')}:`);
            lines.push('');

            items.forEach((item, idx) => {
                lines.push(`${idx + 1}. ${item.nama_lengkap} (${item.kelas || '-'})`);
                lines.push(`   Tanggal: ${formatDateTimeWA(item.tanggal)}`);
                if (item.judul) lines.push(`   ${getJudulLabel(kategori)}: ${item.judul}`);
                if (item.keterangan) lines.push(`   Ket: ${item.keterangan}`);
                lines.push('');
            });

            lines.push(`Total: ${items.length} data`);
            lines.push('');
            lines.push('Hormat kami,');
            lines.push('Pengurus Pondok Pesantren');

            return lines.join('\n');
        }

        function getJudulLabel(kategori) {
            let labels = {
                'sakit': 'Diagnosa',
                'izin_keluar': 'Keperluan',
                'izin_pulang': 'Alasan',
                'sambangan': 'Penjenguk',
                'pelanggaran': 'Jenis',
                'paket': 'Isi Paket',
                'hafalan': 'Kitab/Surat'
            };
            return labels[kategori] || 'Judul';
        }

        function getGreeting() {
            let hour = new Date().getHours();
            if (hour >= 5 && hour < 11) return 'Selamat pagi';
            if (hour >= 11 && hour < 15) return 'Selamat siang';
            if (hour >= 15 && hour < 18) return 'Selamat sore';
            return 'Selamat malam';
        }

        // Copy Report
        $('#btn_copy_report').click(function () {
            let text = $('#report_text').val();
            navigator.clipboard.writeText(text).then(() => {
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 })
                    .fire({ icon: 'success', title: 'Teks berhasil disalin!' });
            }).catch(() => {
                // Fallback for older browsers
                $('#report_text').select();
                document.execCommand('copy');
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 })
                    .fire({ icon: 'success', title: 'Teks berhasil disalin!' });
            });
        });
    </script>
@endpush