@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<h4 class="fw-bold mb-4"><i class="fas fa-cog me-2"></i>Pengaturan Aplikasi</h4>

<form method="POST" action="{{ route('admin.pengaturan.update') }}">
    @csrf
    
    <!-- General Settings -->
    <div class="card-custom p-4 mb-4">
        <h5 class="fw-bold mb-4"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Umum</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Aplikasi</label>
                <input type="text" name="app_name" class="form-control" value="{{ $settings['app_name'] }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Sekolah/Pondok</label>
                <input type="text" name="school_name" class="form-control" value="{{ $settings['school_name'] }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Alamat</label>
                <input type="text" name="school_address" class="form-control" value="{{ $settings['school_address'] }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Telepon</label>
                <input type="text" name="school_phone" class="form-control" value="{{ $settings['school_phone'] }}">
            </div>
        </div>
    </div>

    <!-- WhatsApp Settings -->
    <div class="card-custom p-4 mb-4">
        <h5 class="fw-bold mb-4"><i class="fab fa-whatsapp me-2 text-success"></i>WhatsApp Gateway</h5>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">API URL</label>
                <input type="url" name="wa_api_url" class="form-control" value="{{ $settings['wa_api_url'] }}">
                <small class="text-muted">Contoh: http://serverwa.hello-inv.com/send-message</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">API Key</label>
                <input type="text" name="wa_api_key" class="form-control" value="{{ $settings['wa_api_key'] }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Pengirim (Sender)</label>
                <input type="text" name="wa_sender" class="form-control" value="{{ $settings['wa_sender'] }}" placeholder="6281234567890">
            </div>
        </div>
    </div>

    <!-- Location Settings -->
    <div class="card-custom p-4 mb-4">
        <h5 class="fw-bold mb-4"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Lokasi Absensi</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" value="{{ $settings['latitude'] }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" value="{{ $settings['longitude'] }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Radius (meter)</label>
                <input type="number" name="radius_meters" class="form-control" value="{{ $settings['radius_meters'] }}">
                <small class="text-muted">Jarak maksimal dari lokasi untuk absensi</small>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save me-2"></i>Simpan Pengaturan
    </button>
</form>
@endsection