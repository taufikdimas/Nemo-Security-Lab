<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SecureOps Client Portal')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%2306b6d4' d='M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6.5-4 4a.5.5 0 0 1-.7 0l-2-2a.5.5 0 0 1 .7-.7l1.65 1.65 3.65-3.65a.5.5 0 0 1 .7.7z'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @include('layouts.partials.app-styles')
</head>
<body>
@php
    $topbarTitle = trim(strip_tags($__env->getSection('title', 'Dashboard')));
    $portalUser = auth()->user();
@endphp

{{--
  Sidebar portal. SENGAJA hanya berisi 6 route portal — tidak ada satu pun
  link ke /dashboard, /assets, /incidents, /admin/*, /files, atau /import.
  Middleware role:client memang memblokir route internal dengan 403, tapi
  sidebar juga tidak boleh menampilkan pintu masuk ke halaman internal.
--}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <div class="brand-text">
            <div class="brand-name">SecureOps</div>
            <div class="brand-sub">Client Portal</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.projects*') ? 'active' : '' }}" href="{{ route('portal.projects') }}">
                <i class="bi bi-kanban"></i> Proyek Saya
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.incidents*') ? 'active' : '' }}" href="{{ route('portal.incidents') }}">
                <i class="bi bi-clipboard2-pulse"></i> Insiden
            </a>
        </div>

        <div class="nav-section-label">PENTEST</div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.pentest') || request()->routeIs('portal.pentest.show') ? 'active' : '' }}" href="{{ route('portal.pentest') }}">
                <i class="bi bi-shield-check"></i> Hasil Pentest
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.findings*') || request()->routeIs('portal.pentest.findings*') ? 'active' : '' }}" href="{{ route('portal.findings') }}">
                <i class="bi bi-bug"></i> Temuan Keamanan
            </a>
        </div>

        <div class="nav-section-label">DOKUMEN</div>
        <div class="nav-item">
            <a class="nav-link {{ (request()->routeIs('portal.reports') || request()->routeIs('portal.reports.*')) && !request()->routeIs('portal.pentest-reports*') && !request()->routeIs('portal.pentest.reports*') ? 'active' : '' }}" href="{{ route('portal.reports') }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan SOC
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.pentest-reports*') || request()->routeIs('portal.pentest.reports*') ? 'active' : '' }}" href="{{ route('portal.pentest-reports') }}">
                <i class="bi bi-file-earmark-pdf"></i> Laporan Pentest
            </a>
        </div>

        <div class="nav-section-label">Akun</div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.profile*') ? 'active' : '' }}" href="{{ route('portal.profile') }}">
                <i class="bi bi-person"></i> Profil
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="footer-btn-logout"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</aside>

<nav class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Buka navigasi">
            <i class="bi bi-list"></i>
        </button>
        <div class="breadcrumb-nav">
            <span>SecureOps</span>
            <span class="sep">/</span>
            <span class="current">{{ $topbarTitle }}</span>
        </div>
    </div>
    <div class="topbar-right">
        {{-- Tidak ada notification bell: notifikasi internal tidak relevan untuk klien. --}}
        <div class="dropdown">
            <div class="user-menu" data-bs-toggle="dropdown" style="cursor:pointer">
                <img src="{{ $portalUser->avatar_url }}" alt="{{ $portalUser->name }}" class="avatar" style="object-fit: cover; width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border-subtle);">
                <div class="user-info">
                    <div class="user-name">{{ $portalUser->name }}</div>
                    <div class="user-role" style="color: var(--accent-purple)">
                        {{ match ($portalUser->role) {
                            'admin' => 'Administrator',
                            'client' => 'Klien',
                            default => 'Pengguna',
                        } }}
                    </div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('portal.profile') }}"><i class="bi bi-person"></i> Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-wrapper">
    <div class="main-content">
        @include('layouts.partials.flash')

        @yield('content')
    </div>
</div>

<div class="app-footer">
    <small>SecureOps Client Portal &middot; PT Garuda Siber Nusantara &middot; {{ date('Y') }}</small>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.getElementById('sidebar');
        if (toggle && sidebar) {
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 992 && sidebar.classList.contains('open') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        }

        setTimeout(function () {
            document.querySelectorAll('.toast[data-autoclose="1"]').forEach(function (el) {
                el.style.transition = 'opacity .3s ease';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 300);
            });
        }, 5000);
    })();
</script>
@yield('scripts')
</body>
</html>