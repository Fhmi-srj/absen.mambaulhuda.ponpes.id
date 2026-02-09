<!-- Navbar -->
<nav class="navbar navbar-custom fixed-top">
    <div class="container-fluid px-3 px-md-4">
        <div class="d-flex align-items-center">
            <!-- Hamburger only for tablet, hidden on small mobile -->
            <button class="btn-hamburger me-2 d-none d-sm-block d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('beranda') }}">
                <img src="{{ asset('logo-pondok.png') }}" alt="Logo"
                    style="height: 28px; width: auto; margin-right: 8px;">
                <span class="d-none d-sm-inline">{{ config('app.name') }}</span>
                <span class="d-sm-none">{{ config('app.name') }}</span>
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-primary);">
                    <span class="d-none d-sm-inline text-muted small me-1">
                        {{ auth()->user()->name ?? 'Guest' }}
                    </span>
                    <span class="d-sm-none">
                        <i class="fas fa-user-circle" style="font-size: 1.25rem; color: var(--primary-color);"></i>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileDropdown" style="min-width: 180px;">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-bold" style="color: var(--text-primary);">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <small class="text-muted">{{ ucfirst(auth()->user()->role ?? '') }}</small>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('profil') }}">
                            <i class="fas fa-user me-2 text-muted"></i> Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Profile dropdown styling */
    .dropdown-toggle::after {
        display: none;
    }
    
    .dropdown-menu {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.5rem 0;
    }
    
    .dropdown-item {
        border-radius: 8px;
        margin: 0 0.5rem;
        width: calc(100% - 1rem);
    }
    
    .dropdown-item:hover {
        background-color: var(--hover-bg);
    }
</style>

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