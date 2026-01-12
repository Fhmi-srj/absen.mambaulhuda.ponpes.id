@extends('layouts.app')

@section('title', 'Data Induk Santri')

@push('styles')
    <style>
        .table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
            max-width: 100%;
            max-height: 520px;
        }

        .table-santri thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-santri {
            font-size: 0.72rem;
            white-space: nowrap;
            min-width: 5500px;
            border-collapse: collapse;
        }

        .table-santri thead tr {
            background: #3b5998 !important;
        }

        .table-santri thead th {
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.68rem;
            padding: 0.6rem 0.5rem;
            border: 1px solid #2d4373 !important;
            background: #3b5998 !important;
        }

        .table-santri tbody td {
            padding: 0.45rem 0.5rem;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            background: white;
        }

        .table-santri tbody tr:hover td {
            background: #f1f5f9;
        }

        .sort-header {
            color: white !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }

        .sort-header:hover {
            color: #bfdbfe !important;
        }

        .sort-icons {
            display: inline-flex;
            flex-direction: column;
            font-size: 0.5rem;
            line-height: 0.45;
            opacity: 0.6;
        }

        .sort-header.active .sort-icons {
            opacity: 1;
        }

        .sort-header.asc .sort-icons .fa-caret-up {
            color: #fcd34d;
        }

        .sort-header.desc .sort-icons .fa-caret-down {
            color: #fcd34d;
        }

        .badge-doc {
            font-size: 0.6rem;
            padding: 0.15rem 0.35rem;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            z-index: 5;
        }

        .sticky-col-end {
            position: sticky;
            right: 0;
            z-index: 5;
        }

        thead .sticky-col,
        thead .sticky-col-end {
            z-index: 15;
        }

        #editModal .modal-dialog {
            max-height: calc(100vh - 3.5rem);
            margin: 1.75rem auto;
        }

        #editModal .modal-content {
            max-height: calc(100vh - 3.5rem);
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        #editModal .modal-header {
            flex-shrink: 0;
        }

        #editModal .modal-body {
            overflow-y: auto;
            flex: 1;
            padding: 1.5rem;
            max-height: calc(100vh - 200px);
        }

        #editModal .modal-footer {
            flex-shrink: 0;
            background: #f8f9fa;
        }

        .nav-pills-custom .nav-link {
            border-radius: 0;
            border-bottom: 2px solid transparent;
            color: #6c757d;
            padding: 0.75rem 1.25rem;
            background: none;
        }

        .nav-pills-custom .nav-link.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            font-weight: 600;
        }

        .form-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .form-section h6 {
            color: #3b82f6;
            margin-bottom: 1rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-user-graduate me-2"></i>Data Induk Santri</h5>
            <small class="text-muted">Total: {{ $total }} santri</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
            onclick="resetForm()">
            <i class="fas fa-plus me-1"></i>Tambah Santri
        </button>
    </div>

    <!-- Filter -->
    <div class="card-custom p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-10">
                <form class="row g-2 align-items-end">
                    <input type="hidden" name="sort" value="{{ $sortCol }}">
                    <input type="hidden" name="dir" value="{{ $sortDir }}">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Cari</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama/NISN/NIK/WA..."
                            value="{{ $search }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="AKTIF" {{ $filterStatus === 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                            <option value="NON-AKTIF" {{ $filterStatus === 'NON-AKTIF' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="LULUS" {{ $filterStatus === 'LULUS' ? 'selected' : '' }}>Lulus</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Kelas</label>
                        <select name="kelas" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ $filterKelas === $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-success btn-sm w-100 d-none" id="btnCetakKartu" onclick="cetakKartuTerpilih()">
                    <i class="fas fa-id-card me-1"></i>Cetak (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-custom">
        <div class="table-wrapper">
            <table class="table table-santri mb-0">
                <thead>
                    <tr>
                        <th class="sticky-col" style="width:30px"><input type="checkbox" id="checkAll" onclick="toggleCheckAll()"></th>
                        <th>NO</th>
                        <th><a href="?sort=nama_lengkap&dir={{ $sortCol === 'nama_lengkap' && $sortDir === 'ASC' ? 'DESC' : 'ASC' }}&search={{ $search }}&status={{ $filterStatus }}&kelas={{ $filterKelas }}"
                                class="sort-header {{ $sortCol === 'nama_lengkap' ? 'active ' . strtolower($sortDir) : '' }}">NAMA
                                <span class="sort-icons"><i class="fas fa-caret-up"></i><i class="fas fa-caret-down"></i></span></a></th>
                        <th><a href="?sort=kelas&dir={{ $sortCol === 'kelas' && $sortDir === 'ASC' ? 'DESC' : 'ASC' }}&search={{ $search }}&status={{ $filterStatus }}&kelas={{ $filterKelas }}"
                                class="sort-header {{ $sortCol === 'kelas' ? 'active ' . strtolower($sortDir) : '' }}">KELAS
                                <span class="sort-icons"><i class="fas fa-caret-up"></i><i class="fas fa-caret-down"></i></span></a></th>
                        <th>QURAN</th>
                        <th>KATEGORI</th>
                        <th>NISN</th>
                        <th>NIK</th>
                        <th>NO KK</th>
                        <th>SEKOLAH</th>
                        <th>STATUS</th>
                        <th>TTL</th>
                        <th>JK</th>
                        <th>JML SAUDARA</th>
                        <th>ALAMAT</th>
                        <th>KECAMATAN</th>
                        <th>KABUPATEN</th>
                        <th>ASAL SEKOLAH</th>
                        <th>STATUS MUKIM</th>
                        <th>NAMA AYAH</th>
                        <th>NIK AYAH</th>
                        <th>TTL AYAH</th>
                        <th>PEKERJAAN AYAH</th>
                        <th>PENGHASILAN AYAH</th>
                        <th>NAMA IBU</th>
                        <th>NIK IBU</th>
                        <th>TTL IBU</th>
                        <th>PEKERJAAN IBU</th>
                        <th>PENGHASILAN IBU</th>
                        <th>NO WA</th>
                        <th>RFID</th>
                        <th>NO PIP</th>
                        <th>SUMBER INFO</th>
                        <th>PRESTASI</th>
                        <th>TINGKAT</th>
                        <th>JUARA</th>
                        <th>FOTO</th>
                        <th>DOKUMEN</th>
                        <th class="sticky-col-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santriList as $i => $s)
                        <tr>
                            <td class="sticky-col"><input type="checkbox" class="santri-check" value="{{ $s->id }}" data-nama="{{ $s->nama_lengkap }}" onchange="updateSelectedCount()"></td>
                            <td>{{ ($santriList->currentPage() - 1) * $santriList->perPage() + $loop->iteration }}</td>
                            <td><strong>{{ $s->nama_lengkap }}</strong></td>
                            <td>{{ $s->kelas ?? '-' }}</td>
                            <td>{{ $s->quran ?? '-' }}</td>
                            <td>{{ $s->kategori ?? '-' }}</td>
                            <td><code style="color:#dc3545">{{ $s->nisn ?? '-' }}</code></td>
                            <td>{{ $s->nik ?? '-' }}</td>
                            <td>{{ $s->nomor_kk ?? '-' }}</td>
                            <td>{{ $s->lembaga_sekolah ?? '-' }}</td>
                            <td>
                                @if($s->status === 'AKTIF')<span class="badge bg-success badge-doc">Aktif</span>
                                @elseif($s->status === 'LULUS')<span class="badge bg-info badge-doc">Lulus</span>
                                @else<span class="badge bg-secondary badge-doc">{{ $s->status ?? '-' }}</span>@endif
                            </td>
                            <td>{{ $s->tempat_lahir ?? '-' }}{{ $s->tanggal_lahir ? ', ' . date('d/m/Y', strtotime($s->tanggal_lahir)) : '' }}</td>
                            <td>{{ $s->jenis_kelamin == 'L' ? 'L' : ($s->jenis_kelamin == 'P' ? 'P' : '-') }}</td>
                            <td>{{ $s->jumlah_saudara ?? '-' }}</td>
                            <td style="max-width:200px; white-space:normal;">{{ $s->alamat ?? '-' }}</td>
                            <td>{{ $s->kecamatan ?? '-' }}</td>
                            <td>{{ $s->kabupaten ?? '-' }}</td>
                            <td>{{ $s->asal_sekolah ?? '-' }}</td>
                            <td>{{ $s->status_mukim ?? '-' }}</td>
                            <td>{{ $s->nama_ayah ?? '-' }}</td>
                            <td>{{ $s->nik_ayah ?? '-' }}</td>
                            <td>{{ $s->tempat_lahir_ayah ?? '-' }}{{ $s->tanggal_lahir_ayah ? ', ' . date('d/m/Y', strtotime($s->tanggal_lahir_ayah)) : '' }}</td>
                            <td>{{ $s->pekerjaan_ayah ?? '-' }}</td>
                            <td>{{ $s->penghasilan_ayah ?? '-' }}</td>
                            <td>{{ $s->nama_ibu ?? '-' }}</td>
                            <td>{{ $s->nik_ibu ?? '-' }}</td>
                            <td>{{ $s->tempat_lahir_ibu ?? '-' }}{{ $s->tanggal_lahir_ibu ? ', ' . date('d/m/Y', strtotime($s->tanggal_lahir_ibu)) : '' }}</td>
                            <td>{{ $s->pekerjaan_ibu ?? '-' }}</td>
                            <td>{{ $s->penghasilan_ibu ?? '-' }}</td>
                            <td>{{ $s->no_wa_wali ?? '-' }}</td>
                            <td>{!! $s->nomor_rfid ? '<span class="badge bg-success badge-doc">Ada</span>' : '-' !!}</td>
                            <td>{{ $s->nomor_pip ?? '-' }}</td>
                            <td>{{ $s->sumber_info ?? '-' }}</td>
                            <td>{{ $s->prestasi ?? '-' }}</td>
                            <td>{{ $s->tingkat_prestasi ?? '-' }}</td>
                            <td>{{ $s->juara_prestasi ?? '-' }}</td>
                            <td>{!! $s->foto_santri ? '<span class="badge bg-success badge-doc">Ada</span>' : '-' !!}</td>
                            <td>
                                @if($s->dokumen_kk || $s->dokumen_akte || $s->dokumen_ktp || $s->dokumen_ijazah || $s->dokumen_sertifikat)
                                    <span class="badge bg-success badge-doc">Ada</span>
                                @else - @endif
                            </td>
                            <td class="sticky-col-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-success btn-sm" onclick="showQrModal({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}', '{{ $s->nisn ?? $s->id }}', '{{ $s->kelas ?? '-' }}')" title="Kartu QR"><i class="fas fa-qrcode"></i></button>
                                    <button class="btn btn-primary btn-sm" onclick="editSantri({{ $s->id }})" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" onclick="hapusSantri({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}')" title="Hapus"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="42" class="text-center py-4 text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($santriList->hasPages())
            <div class="p-3 border-top">
                {{ $santriList->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Edit/Create Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form id="formSantri" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTitle"><i class="fas fa-user-edit me-2"></i>Tambah Santri</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills-custom mb-4" id="editTabs">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#tab-santri"><i
                                        class="fas fa-user me-1"></i> Data Santri</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tab-ortu"><i
                                        class="fas fa-users me-1"></i> Data Orang Tua</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tab-dokumen"><i
                                        class="fas fa-file me-1"></i> Dokumen</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- Tab Santri -->
                            <div class="tab-pane fade show active" id="tab-santri">
                                <div class="form-section">
                                    <h6><i class="fas fa-id-card me-2"></i>Identitas</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Nama Lengkap <span
                                                    class="text-danger">*</span></label><input type="text"
                                                name="nama_lengkap" id="edit_nama_lengkap" class="form-control" required>
                                        </div>
                                        <div class="col-md-3"><label class="form-label">NISN</label><input type="text"
                                                name="nisn" id="edit_nisn" class="form-control"></div>
                                        <div class="col-md-3"><label class="form-label">NIK</label><input type="text"
                                                name="nik" id="edit_nik" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Nomor KK</label><input type="text"
                                                name="nomor_kk" id="edit_nomor_kk" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input
                                                type="text" name="tempat_lahir" id="edit_tempat_lahir" class="form-control">
                                        </div>
                                        <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input
                                                type="date" name="tanggal_lahir" id="edit_tanggal_lahir"
                                                class="form-control"></div>
                                        <div class="col-md-3"><label class="form-label">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="edit_jenis_kelamin" class="form-select">
                                                <option value="">- Pilih -</option>
                                                <option value="L">L (Laki-laki)</option>
                                                <option value="P">P (Perempuan)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"><label class="form-label">Jumlah Saudara</label><input
                                                type="number" name="jumlah_saudara" id="edit_jumlah_saudara"
                                                class="form-control" min="0"></div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h6><i class="fas fa-school me-2"></i>Pendidikan</h6>
                                    <div class="row g-3">
                                        <div class="col-md-3"><label class="form-label">Lembaga Sekolah</label>
                                            <select name="lembaga_sekolah" id="edit_lembaga_sekolah" class="form-select">
                                                <option value="">- Pilih -</option>
                                                <option value="SMP NU BP">SMP NU BP</option>
                                                <option value="MA ALHIKAM">MA ALHIKAM</option>
                                                <option value="ITS">ITS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"><label class="form-label">Kelas</label><input type="text"
                                                name="kelas" id="edit_kelas" class="form-control"></div>
                                        <div class="col-md-3"><label class="form-label">Quran</label><input type="text"
                                                name="quran" id="edit_quran" class="form-control"></div>
                                        <div class="col-md-3"><label class="form-label">Kategori</label><input type="text"
                                                name="kategori" id="edit_kategori" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Status</label>
                                            <select name="status" id="edit_status" class="form-select">
                                                <option value="AKTIF">AKTIF</option>
                                                <option value="NON-AKTIF">NON-AKTIF</option>
                                                <option value="LULUS">LULUS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4"><label class="form-label">Asal Sekolah</label><input
                                                type="text" name="asal_sekolah" id="edit_asal_sekolah" class="form-control">
                                        </div>
                                        <div class="col-md-4"><label class="form-label">Status Mukim</label>
                                            <select name="status_mukim" id="edit_status_mukim" class="form-select">
                                                <option value="">- Pilih -</option>
                                                <option value="PONDOK PP MAMBAUL HUDA">PONDOK PP MAMBAUL HUDA</option>
                                                <option value="PONDOK SELAIN PP MAMBAUL HUDA">PONDOK SELAIN</option>
                                                <option value="TIDAK PONDOK">TIDAK PONDOK</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h6><i class="fas fa-map-marker-alt me-2"></i>Alamat & Kontak</h6>
                                    <div class="row g-3">
                                        <div class="col-12"><label class="form-label">Alamat Lengkap</label><textarea
                                                name="alamat" id="edit_alamat" class="form-control" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-4"><label class="form-label">Kecamatan</label><input type="text"
                                                name="kecamatan" id="edit_kecamatan" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Kabupaten</label><input type="text"
                                                name="kabupaten" id="edit_kabupaten" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">No WA Wali</label><input type="text"
                                                name="no_wa_wali" id="edit_no_wa_wali" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Nomor RFID</label><input type="text"
                                                name="nomor_rfid" id="edit_nomor_rfid" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">No. PIP/PKH</label><input
                                                type="text" name="nomor_pip" id="edit_nomor_pip" class="form-control"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Orang Tua -->
                            <div class="tab-pane fade" id="tab-ortu">
                                <div class="form-section">
                                    <h6><i class="fas fa-male me-2"></i>Data Ayah</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Nama Ayah</label><input type="text"
                                                name="nama_ayah" id="edit_nama_ayah" class="form-control"></div>
                                        <div class="col-md-6"><label class="form-label">NIK Ayah</label><input type="text"
                                                name="nik_ayah" id="edit_nik_ayah" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Tempat Lahir Ayah</label><input
                                                type="text" name="tempat_lahir_ayah" id="edit_tempat_lahir_ayah"
                                                class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Tanggal Lahir Ayah</label><input
                                                type="date" name="tanggal_lahir_ayah" id="edit_tanggal_lahir_ayah"
                                                class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Pekerjaan Ayah</label><input
                                                type="text" name="pekerjaan_ayah" id="edit_pekerjaan_ayah"
                                                class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Penghasilan Ayah</label>
                                            <select name="penghasilan_ayah" id="edit_penghasilan_ayah" class="form-select">
                                                <option value="">- Pilih -</option>
                                                <option value="Di bawah Rp. 1.000.000">Di bawah Rp. 1.000.000</option>
                                                <option value="Di bawah Rp. 2.500.000">Di bawah Rp. 2.500.000</option>
                                                <option value="Di bawah Rp. 4.000.000">Di bawah Rp. 4.000.000</option>
                                                <option value="Di atas Rp. 4.000.000">Di atas Rp. 4.000.000</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h6><i class="fas fa-female me-2"></i>Data Ibu</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Nama Ibu</label><input type="text"
                                                name="nama_ibu" id="edit_nama_ibu" class="form-control"></div>
                                        <div class="col-md-6"><label class="form-label">NIK Ibu</label><input type="text"
                                                name="nik_ibu" id="edit_nik_ibu" class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Tempat Lahir Ibu</label><input
                                                type="text" name="tempat_lahir_ibu" id="edit_tempat_lahir_ibu"
                                                class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Tanggal Lahir Ibu</label><input
                                                type="date" name="tanggal_lahir_ibu" id="edit_tanggal_lahir_ibu"
                                                class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Pekerjaan Ibu</label><input
                                                type="text" name="pekerjaan_ibu" id="edit_pekerjaan_ibu"
                                                class="form-control"></div>
                                        <div class="col-md-4"><label class="form-label">Penghasilan Ibu</label>
                                            <select name="penghasilan_ibu" id="edit_penghasilan_ibu" class="form-select">
                                                <option value="">- Pilih -</option>
                                                <option value="Di bawah Rp. 1.000.000">Di bawah Rp. 1.000.000</option>
                                                <option value="Di bawah Rp. 2.500.000">Di bawah Rp. 2.500.000</option>
                                                <option value="Di bawah Rp. 4.000.000">Di bawah Rp. 4.000.000</option>
                                                <option value="Di atas Rp. 4.000.000">Di atas Rp. 4.000.000</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Dokumen -->
                            <div class="tab-pane fade" id="tab-dokumen">
                                <div class="form-section">
                                    <h6><i class="fas fa-file me-2"></i>Upload Dokumen</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Foto Santri</label><input
                                                type="file" name="foto_santri" class="form-control" accept="image/*"></div>
                                        <div class="col-md-6"><label class="form-label">Kartu Keluarga (KK)</label><input
                                                type="file" name="dokumen_kk" class="form-control" accept="image/*,.pdf">
                                        </div>
                                        <div class="col-md-6"><label class="form-label">Akte Kelahiran</label><input
                                                type="file" name="dokumen_akte" class="form-control" accept="image/*,.pdf">
                                        </div>
                                        <div class="col-md-6"><label class="form-label">KTP Wali</label><input type="file"
                                                name="dokumen_ktp" class="form-control" accept="image/*,.pdf"></div>
                                        <div class="col-md-6"><label class="form-label">Ijazah</label><input type="file"
                                                name="dokumen_ijazah" class="form-control" accept="image/*,.pdf"></div>
                                        <div class="col-md-6"><label class="form-label">Sertifikat</label><input type="file"
                                                name="dokumen_sertifikat" class="form-control" accept="image/*,.pdf"></div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Format: JPG, PNG, GIF, PDF. Max 2MB per
                                        file.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let isEditing = false;

        function resetForm() {
            isEditing = false;
            document.getElementById('edit_id').value = '';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i>Tambah Santri';
            document.querySelectorAll('#formSantri input:not([type="file"]), #formSantri textarea, #formSantri select').forEach(el => {
                if (el.type !== 'hidden' && el.name !== '_token') {
                    if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    } else {
                        el.value = '';
                    }
                }
            });
            document.getElementById('edit_status').value = 'AKTIF';
        }

        function editSantri(id) {
            isEditing = true;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit Data Santri';

            fetch('{{ url("api/admin/santri") }}/' + id)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const s = data.data;
                        document.getElementById('edit_id').value = s.id;

                        // Fill all fields
                        const fields = ['nama_lengkap', 'nisn', 'nik', 'nomor_kk', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'jumlah_saudara', 'lembaga_sekolah', 'kelas', 'quran', 'kategori', 'status', 'asal_sekolah', 'status_mukim', 'alamat', 'kecamatan', 'kabupaten', 'no_wa_wali', 'nomor_rfid', 'nomor_pip', 'nama_ayah', 'nik_ayah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'nama_ibu', 'nik_ibu', 'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'pekerjaan_ibu', 'penghasilan_ibu'];

                        fields.forEach(f => {
                            const el = document.getElementById('edit_' + f);
                            if (el) el.value = s[f] || '';
                        });

                        new bootstrap.Modal(document.getElementById('editModal')).show();
                    }
                });
        }

        document.getElementById('formSantri').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const url = isEditing
                ? '{{ url("api/admin/santri") }}/' + id
                : '{{ route("api.admin.santri.store") }}';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
        });

        function hapusSantri(id, name) {
            Swal.fire({
                title: 'Hapus Santri?',
                text: 'Santri "' + name + '" akan dipindahkan ke trash',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('{{ url("api/admin/santri") }}/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                }
            });
        }

        // Checkbox functions
        function toggleCheckAll() {
            const checkAll = document.getElementById('checkAll');
            document.querySelectorAll('.santri-check').forEach(cb => cb.checked = checkAll.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.santri-check:checked');
            const count = checked.length;
            document.getElementById('selectedCount').textContent = count;
            const btn = document.getElementById('btnCetakKartu');
            if (count > 0) {
                btn.classList.remove('d-none');
            } else {
                btn.classList.add('d-none');
            }
        }

        function cetakKartuTerpilih() {
            const checked = document.querySelectorAll('.santri-check:checked');
            if (checked.length === 0) {
                Swal.fire('Pilih Santri', 'Pilih minimal 1 santri untuk cetak kartu', 'warning');
                return;
            }
            const ids = Array.from(checked).map(cb => cb.value).join(',');
            window.open('{{ route("cetak-kartu") }}?ids=' + ids, '_blank');
        }

        // Load QRCode.js and html2canvas for card generation
        (function loadQrLibrary() {
            if (typeof QRCode === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
                document.head.appendChild(script);
            }
            if (typeof html2canvas === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
                document.head.appendChild(script);
            }
        })();

        // Store card data for print/download (persists after modal closes)
        let savedCardHtml = '';
        let savedCardName = '';

        // QR Modal
        async function showQrModal(id, nama, nisn, kelas) {
            const logoUrl = '{{ asset("logo-pondok.png") }}';
            
            // Calculate font size based on name length
            let nameFontSize = 14;
            if (nama.length > 25) nameFontSize = 11;
            else if (nama.length > 20) nameFontSize = 12;
            else if (nama.length > 15) nameFontSize = 13;
            
            // Truncate NISN to first 6 digits for display
            const displayNisn = nisn ? nisn.toString().substring(0, 6) : '------';
            
            // Generate QR code as data URL using QRCode.js (avoids CORS issues)
            let qrDataUrl = '';
            try {
                qrDataUrl = await QRCode.toDataURL(nisn.toString(), {
                    width: 180,
                    margin: 0,
                    color: { dark: '#000000', light: '#ffffff' }
                });
            } catch (err) {
                console.error('QR generation error:', err);
                // Fallback to external API if QRCode.js fails
                qrDataUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(nisn);
            }
            
            // Convert logo to data URL for proper export
            let logoDataUrl = logoUrl;
            try {
                const response = await fetch(logoUrl);
                const blob = await response.blob();
                logoDataUrl = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });
            } catch (err) {
                console.log('Logo fetch error, using original URL');
            }
            
            // Build card HTML with embedded data URLs
            const cardHtml = `
                <div id="qrCardContainer" style="background: linear-gradient(135deg, #1e3a5f 0%, #3b82f6 50%, #60a5fa 100%); border-radius: 16px; padding: 20px 20px 25px 20px; color: white; width: 340px; height: 195px; text-align: left; margin: 0 auto; box-sizing: border-box;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div>
                            <div style="font-size: 9px; text-transform: uppercase; opacity: 0.9; font-family: sans-serif;">Pondok Pesantren Mambaul Huda</div>
                            <div style="font-size: 11px; font-weight: 700; color: #fbbf24; font-family: sans-serif;">KARTU SANTRI</div>
                        </div>
                        <img src="${logoDataUrl}" style="width: 35px; height: 35px; border-radius: 50%; background: rgba(255,255,255,0.2); padding: 3px; object-fit: contain;">
                    </div>
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="background: white; padding: 6px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                            <img src="${qrDataUrl}" style="width: 90px; height: 90px; display: block;">
                        </div>
                        <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; height: 102px;">
                            <div>
                                <div style="font-size: ${nameFontSize}px; font-weight: 700; word-break: break-word; line-height: 1.2; font-family: sans-serif;">${nama}</div>
                                <div style="font-size: 16px; font-weight: 700; font-family: 'Courier New', monospace; color: #fbbf24; letter-spacing: 2px; margin-top: 4px;">${displayNisn}</div>
                                <div style="font-size: 10px; opacity: 0.8; margin-top: 4px;"><span style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 12px; font-family: sans-serif;">Kelas ${kelas}</span></div>
                            </div>
                            <div style="font-size: 8px; opacity: 0.7; font-family: sans-serif; text-align: right;">Scan QR untuk absensi</div>
                        </div>
                    </div>
                </div>
            `;
            
            // Save for later use (print/download after modal closes)
            savedCardHtml = cardHtml;
            savedCardName = nama;
            
            Swal.fire({
                title: '<i class="fas fa-id-card text-success me-2"></i>Kartu QR',
                html: cardHtml,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-print me-1"></i> Cetak',
                denyButtonText: '<i class="fas fa-download me-1"></i> Download',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#3b82f6',
                denyButtonColor: '#10b981',
                width: 420
            }).then(async result => {
                if (result.isConfirmed) {
                    // Print - open new window with proper styling
                    printCard();
                } else if (result.isDenied) {
                    // Download as PNG
                    await downloadCard();
                }
            });
        }
        
        function printCard() {
            // Remove any existing print iframe
            const existingFrame = document.getElementById('printFrame');
            if (existingFrame) existingFrame.remove();
            
            // Create hidden iframe for printing (stays on same page)
            const iframe = document.createElement('iframe');
            iframe.id = 'printFrame';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);
            
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Kartu ${savedCardName}</title>
                    <style>
                        @page {
                            size: A4 portrait;
                            margin: 10mm;
                        }
                        * {
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            color-adjust: exact !important;
                        }
                        html, body {
                            margin: 0;
                            padding: 0;
                            width: 100%;
                            height: 100%;
                        }
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: flex-start;
                            padding-top: 20mm;
                            background: white;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        }
                        #qrCardContainer {
                            transform: scale(1.5);
                            transform-origin: top center;
                        }
                    </style>
                </head>
                <body>
                    ${savedCardHtml}
                </body>
                </html>
            `);
            doc.close();
            
            // Wait for images to load then print
            iframe.onload = function() {
                const images = iframe.contentWindow.document.querySelectorAll('img');
                let loaded = 0;
                const total = images.length;
                
                function tryPrint() {
                    loaded++;
                    if (loaded >= total) {
                        setTimeout(() => {
                            iframe.contentWindow.print();
                        }, 200);
                    }
                }
                
                if (total === 0) {
                    setTimeout(() => iframe.contentWindow.print(), 200);
                } else {
                    images.forEach(img => {
                        if (img.complete) tryPrint();
                        else {
                            img.onload = tryPrint;
                            img.onerror = tryPrint;
                        }
                    });
                }
            };
        }
        
        async function downloadCard() {
            // Create a temporary container for html2canvas
            const tempContainer = document.createElement('div');
            tempContainer.innerHTML = savedCardHtml;
            tempContainer.style.position = 'fixed';
            tempContainer.style.left = '-9999px';
            tempContainer.style.top = '0';
            document.body.appendChild(tempContainer);
            
            const card = tempContainer.querySelector('#qrCardContainer');
            
            // Show loading
            Swal.fire({
                title: 'Mengunduh...',
                html: '<div class="spinner-border text-success"></div><br><small>Menyiapkan gambar kartu...</small>',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            
            try {
                // Wait for images in temp container to load
                const images = card.querySelectorAll('img');
                await Promise.all(Array.from(images).map(img => {
                    return new Promise((resolve) => {
                        if (img.complete) resolve();
                        else {
                            img.onload = resolve;
                            img.onerror = resolve;
                        }
                    });
                }));
                
                // Small delay
                await new Promise(r => setTimeout(r, 100));
                
                const canvas = await html2canvas(card, { 
                    scale: 3,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: null,
                    logging: false
                });
                
                const link = document.createElement('a');
                link.download = 'kartu_' + savedCardName.replace(/\W+/g, '_') + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kartu berhasil diunduh',
                    timer: 1500,
                    showConfirmButton: false
                });
            } catch (err) {
                console.error('Download error:', err);
                Swal.fire('Gagal', 'Tidak dapat mengunduh kartu: ' + err.message, 'error');
            } finally {
                // Clean up temp container
                document.body.removeChild(tempContainer);
            }
        }
    </script>
@endpush