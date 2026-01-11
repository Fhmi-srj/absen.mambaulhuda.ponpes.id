@extends('layouts.app')

@section('title', 'Profil')

@push('styles')
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #a78bfa 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            margin-bottom: 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .profile-role {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
        }

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
            border: 1px solid #e2e8f0 !important;
        }

        .btn-file:hover {
            background: #f1f5f9;
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
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <img src="{{ $user->foto && $user->foto !== 'profile.jpg' ? asset('uploads/profiles/' . $user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=8659F1&color=fff&size=100' }}"
                alt="Profile" class="profile-avatar">
            <div>
                <div class="profile-name">{{ $user->name }}</div>
                <div class="opacity-75 mb-2">{{ $user->email }}</div>
                <span class="profile-role">
                    {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Edit Profile Form -->
        <div class="col-lg-6">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
                <form action="{{ route('profil.update-data') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <small class="text-muted">Email tidak dapat diubah</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ $user->address ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password & Photo -->
        <div class="col-lg-6">
            <!-- Change Photo -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-camera me-2"></i>Ubah Foto</h5>
                <form action="{{ route('profil.update-foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <div class="photo-upload-wrapper" id="wrapper_foto">
                            <input type="file" name="foto" id="input_foto" class="d-none" accept="image/*" required>
                            <input type="file" id="camera_foto" class="d-none" accept="image/*" capture="environment">
                            <div class="photo-upload-buttons" id="buttons_foto">
                                <button type="button" class="btn-photo-upload btn-camera"
                                    onclick="document.getElementById('camera_foto').click()">
                                    <i class="fas fa-camera"></i> Ambil Foto
                                </button>
                                <button type="button" class="btn-photo-upload btn-file"
                                    onclick="document.getElementById('input_foto').click()">
                                    <i class="fas fa-folder-open"></i> Pilih File
                                </button>
                            </div>
                            <div class="photo-preview-container d-none" id="container_foto">
                                <img id="preview_foto" alt="Preview">
                                <button type="button" class="btn-remove-photo" onclick="removePhoto()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG, GIF. Max 2MB</small>
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-upload me-1"></i> Upload Foto
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-key me-2"></i>Ubah Password</h5>
                <form action="{{ route('profil.update-password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock me-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Handle photo selection
        document.getElementById('input_foto').addEventListener('change', function () {
            handlePhoto(this);
        });
        document.getElementById('camera_foto').addEventListener('change', function () {
            if (this.files && this.files[0]) {
                let mainInput = document.getElementById('input_foto');
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(this.files[0]);
                mainInput.files = dataTransfer.files;
                handlePhoto(mainInput);
            }
        });

        function handlePhoto(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview_foto').src = e.target.result;
                    document.getElementById('container_foto').classList.remove('d-none');
                    document.getElementById('buttons_foto').classList.add('d-none');
                    document.getElementById('wrapper_foto').classList.add('has-preview');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePhoto() {
            document.getElementById('input_foto').value = '';
            document.getElementById('camera_foto').value = '';
            document.getElementById('preview_foto').src = '';
            document.getElementById('container_foto').classList.add('d-none');
            document.getElementById('buttons_foto').classList.remove('d-none');
            document.getElementById('wrapper_foto').classList.remove('has-preview');
        }
    </script>
@endpush