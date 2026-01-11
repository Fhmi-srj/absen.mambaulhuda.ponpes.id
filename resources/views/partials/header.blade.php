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
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</nav>