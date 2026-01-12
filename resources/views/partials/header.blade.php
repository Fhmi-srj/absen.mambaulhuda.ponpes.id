<!-- Navbar -->
<nav class="navbar navbar-custom fixed-top">
    <div class="container-fluid px-3 px-md-4">
        <div class="d-flex align-items-center">
            <button class="btn-hamburger me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('beranda') }}">
                <img src="{{ asset('logo-pondok.png') }}" alt="Logo"
                    style="height: 28px; width: auto; margin-right: 8px;">
                {{ config('app.name') }}
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <span class="text-muted small d-none d-sm-inline">
                {{ auth()->user()->name ?? 'Guest' }} ({{ auth()->user()->role ?? '' }})
            </span>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4" style="overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                    <i class="fas fa-sign-out-alt text-white" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Konfirmasi Logout</h5>
                <p class="text-muted mb-4">Apakah Anda yakin ingin keluar dari sistem?</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 py-2 fw-bold rounded-3 mb-2">
                        <i class="fas fa-sign-out-alt me-2"></i> Ya, Logout
                    </button>
                </form>
                <button type="button" class="btn btn-light w-100 py-2 rounded-3" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>