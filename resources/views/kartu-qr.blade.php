@extends('layouts.app')
@section('title', 'Kartu Santri - ' . $siswa->nama_lengkap)

@push('styles')
    <style>
        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
        }

        .id-card {
            width: 340px;
            height: 195px;
            background: linear-gradient(135deg, #1e3a5f 0%, #3b82f6 50%, #60a5fa 100%);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(30, 58, 95, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            color: white;
        }

        .id-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .id-card::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .card-header-custom {
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        .school-info {
            flex: 1;
        }

        .school-name {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            line-height: 1.3;
        }

        .card-title-custom {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
            color: #fbbf24;
        }

        .card-logo {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-body-custom {
            display: flex;
            padding: 0 16px 20px 16px;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .qr-section {
            background: white;
            padding: 6px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .qr-section img {
            display: block;
            width: 90px;
            height: 90px;
        }

        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 102px;
        }

        .info-content {
            display: flex;
            flex-direction: column;
        }

        .scan-text {
            font-size: 8px;
            opacity: 0.7;
            text-align: right;
        }

        .student-name {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
        }

        .student-nis {
            font-size: 16px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            margin-bottom: 6px;
            color: #fbbf24;
        }

        .student-class {
            font-size: 10px;
            opacity: 0.8;
        }

        .student-class span {
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 500;
        }



        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 24px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .sidebar,
            .navbar {
                display: none !important;
            }

            .card-container {
                min-height: auto;
                padding: 0;
            }

            .id-card {
                box-shadow: none;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="mb-3 no-print">
        <a href="{{ route('admin.santri') }}" class="btn btn-light border"><i class="fas fa-arrow-left me-1"></i>
            Kembali</a>
    </div>

    <div class="card-container">
        <div>
            <div class="id-card">
                <div class="card-header-custom">
                    <div class="school-info">
                        <div class="school-name">{{ $schoolName }}</div>
                        <div class="card-title-custom">Kartu Santri</div>
                    </div>
                    <div class="card-logo">
                        <img src="{{ asset('logo-pondok.png') }}" alt="Logo"
                            style="width: 30px; height: 30px; object-fit: contain;">
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="qr-section">
                        <img id="qrImage" src="" alt="QR Code">
                    </div>
                    <div class="info-section">
                        <div class="info-content">
                            <div class="student-name">{{ $siswa->nama_lengkap }}</div>
                            <div class="student-nis">{{ $siswa->nisn ?? '-' }}</div>
                            <div class="student-class"><span>Kelas {{ $siswa->kelas }}</span></div>
                        </div>
                        <div class="scan-text">Scan QR untuk absensi</div>
                    </div>
                </div>
            </div>

            <div class="action-buttons no-print">
                <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-1"></i> Cetak
                    Kartu</button>
                <button onclick="downloadCard()" class="btn btn-success"><i class="fas fa-download me-1"></i> Download
                    Kartu</button>
            </div>
        </div>
    </div>

    <div class="text-center text-muted mt-4 small no-print">
        <i class="fas fa-info-circle me-1"></i> Ukuran kartu sesuai standar kartu ATM (85.6 × 54 mm)
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        // Generate QR code as data URL and set to img element
        document.addEventListener('DOMContentLoaded', function() {
            const qrData = '{{ $siswa->nisn ?? $siswa->id }}';
            const qrImage = document.getElementById('qrImage');
            
            QRCode.toDataURL(qrData, {
                width: 90,
                margin: 0,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            }).then(url => {
                qrImage.src = url;
            }).catch(err => {
                console.error('QR Error:', err);
            });
        });

        async function downloadCard() {
            const card = document.querySelector('.id-card');
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyiapkan...';
            btn.disabled = true;

            try {
                // Wait a bit to ensure QR is fully rendered
                await new Promise(r => setTimeout(r, 100));
                
                const canvas = await html2canvas(card, { 
                    scale: 3, 
                    useCORS: true, 
                    allowTaint: true,
                    backgroundColor: null,
                    logging: false
                });
                
                const link = document.createElement('a');
                link.download = 'Kartu_{{ $siswa->nisn ?? $siswa->id }}_{{ preg_replace("/[^a-zA-Z0-9]/", "_", $siswa->nama_lengkap) }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            } catch(err) {
                console.error('Download error:', err);
                alert('Gagal menyimpan kartu. Silakan gunakan tombol Cetak.');
            }
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    </script>
@endpush