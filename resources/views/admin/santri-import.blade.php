@extends('layouts.app')
@section('title', 'Import Santri')

@push('styles')
    <style>
        .column-table {
            font-size: 0.8rem;
        }

        .column-table th,
        .column-table td {
            padding: 0.4rem 0.6rem;
        }

        .badge-wajib {
            background: #dc3545;
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .badge-opsional {
            background: #6c757d;
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .note-text {
            font-size: 0.7rem;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button"
                class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <h4 class="fw-bold mb-4"><i class="fas fa-file-import me-2"></i>Import Santri</h4>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-upload me-2 text-primary"></i>Upload File Excel</h5>

                @if(!empty($errors))
                    <div class="alert alert-danger">
                        <strong><i class="fas fa-exclamation-triangle me-1"></i>Error:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach(array_slice($errors, 0, 10) as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                            @if(count($errors) > 10)
                                <li><em>...dan {{ count($errors) - 10 }} error lainnya</em></li>
                            @endif
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.santri-import.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">File Excel (.xlsx)</label>
                        <input type="file" name="file_santri" class="form-control form-control-lg" accept=".xlsx,.xls"
                            required>
                        <small class="text-muted">Format: Microsoft Excel (.xlsx atau .xls)</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fas fa-upload me-2"></i> Import
                        Data Santri</button>
                </form>

                <hr class="my-4">

                <div class="alert alert-info small mb-0">
                    <h6 class="fw-bold"><i class="fas fa-info-circle me-1"></i>Petunjuk:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Baris pertama = Header (nama kolom)</li>
                        <li>Data dimulai dari baris kedua</li>
                        <li>Kolom <span class="badge badge-wajib">WAJIB</span> harus diisi</li>
                        <li>Kolom <span class="badge badge-opsional">Opsional</span> boleh dikosongkan</li>
                        <li>Data dengan NISN sama akan di-update</li>
                        <li>Data baru tanpa NISN dicek dari Nama + Kelas</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-table me-2 text-success"></i>Format Kolom Excel</h5>
                <p class="text-muted small mb-3">Semua kolom harus ada di file Excel (sesuai urutan). Isi data sesuai
                    kebutuhan.</p>

                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered table-hover column-table mb-0">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th class="text-center" style="width: 50px;">Kolom</th>
                                <th>Nama Field</th>
                                <th>Keterangan</th>
                                <th class="text-center" style="width: 80px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $colLetters = range('A', 'Z');
                            $colIndex = 0; @endphp
                            @foreach($columnDefinitions as $col)
                                @php $letter = $colIndex < 26 ? $colLetters[$colIndex] : 'A' . $colLetters[$colIndex - 26];
                                $colIndex++; @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $letter }}</td>
                                    <td>
                                        <strong>{{ $col['name'] }}</strong>
                                        @if(isset($col['note']))<br><span class="note-text">{{ $col['note'] }}</span>@endif
                                        @if(isset($col['default']))<br><span class="note-text">Default:
                                        {{ $col['default'] }}</span>@endif
                                    </td>
                                    <td>{{ $col['label'] }}</td>
                                    <td class="text-center">
                                        @if($col['required'])
                                            <span class="badge badge-wajib">WAJIB</span>
                                        @else
                                            <span class="badge badge-opsional">Opsional</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-muted small">
                    <i class="fas fa-columns me-1"></i> Total: <strong>{{ count($columnDefinitions) }} kolom</strong>
                </div>
            </div>
        </div>
    </div>
@endsection