<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SecureOps')</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%2306b6d4' d='M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6.5-4 4a.5.5 0 0 1-.7 0l-2-2a.5.5 0 0 1 .7-.7l1.65 1.65 3.65-3.65a.5.5 0 0 1 .7.7z'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @include('layouts.partials.app-styles')
</head>

<body>
    @php
    $navOpenIncidents = \App\Models\Incident::where('status', 'open')->count();
    // Daftar insiden terbuka untuk panel notifikasi di topbar.
    $navRecentIncidents = \App\Models\Incident::with('assignedTo')
    ->where('status', 'open')
    ->latest()
    ->limit(6)
    ->get();
    $topbarTitle = trim(strip_tags($__env->getSection('title', 'Dashboard')));
    @endphp

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="brand-text">
                <div class="brand-name">SecureOps</div>
                <div class="brand-sub">PT Garuda Siber Nusantara</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Ringkasan</div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Beranda
                </a>
            </div>

            <div class="nav-section-label">Operasional</div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('incidents*') ? 'active' : '' }}"
                    href="{{ route('incidents.index') }}">
                    <i class="bi bi-clipboard2-pulse"></i> Insiden
                    @if($navOpenIncidents > 0)
                    <span class="nav-badge">{{ $navOpenIncidents }}</span>
                    @endif
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('assets*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
                    <i class="bi bi-hdd-network"></i> Aset
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('vulndb*') ? 'active' : '' }}" href="{{ route('vulndb.index') }}">
                    <i class="bi bi-bug"></i> Basis Kerentanan
                </a>
            </div>

            <div class="nav-section-label">Pentest</div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('pentest/engagements*') ? 'active' : '' }}"
                    href="{{ route('pentest.engagements.index') }}">
                    <i class="bi bi-shield-check"></i> Engagement Pentest
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('pentest/reports*') ? 'active' : '' }}"
                    href="{{ route('pentest.reports.index') }}">
                    <i class="bi bi-file-earmark-lock2"></i> Laporan Pentest
                </a>
            </div>

            <div class="nav-section-label">Manajemen</div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-kanban"></i> Proyek
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('clients*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                    <i class="bi bi-people"></i> Klien
                </a>
            </div>
            @if(auth()->user()->role === 'admin')
            <div class="nav-item">
                <a class="nav-link {{ request()->is('employees*') ? 'active' : '' }}"
                    href="{{ route('employees.index') }}">
                    <i class="bi bi-person-badge"></i> Team
                </a>
            </div>
            @endif

            <div class="nav-section-label">Perangkat</div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('tools.diagnostic') ? 'active' : '' }}"
                    href="{{ route('tools.diagnostic') }}">
                    <i class="bi bi-broadcast-pin"></i> Diagnostik Jaringan
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('tools.diagnostic.history') ? 'active' : '' }}"
                    href="{{ route('tools.diagnostic.history') }}">
                    <i class="bi bi-clock-history"></i> Riwayat Diagnostik
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan (SOC)
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('files*') ? 'active' : '' }}" href="{{ route('files.index') }}">
                    <i class="bi bi-folder2-open"></i> Berkas
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('import*') ? 'active' : '' }}" href="{{ route('import.index') }}">
                    <i class="bi bi-upload"></i> Impor Data
                </a>
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="nav-section-label">Admin</div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class="bi bi-person-gear"></i> Manajemen Pengguna
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/files*') ? 'active' : '' }}"
                    href="{{ route('admin.files.index') }}">
                    <i class="bi bi-folder-check"></i> Manajemen Berkas
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/config*') ? 'active' : '' }}"
                    href="{{ route('admin.config') }}">
                    <i class="bi bi-sliders"></i> Konfigurasi Sistem
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/threats*') ? 'active' : '' }}"
                    href="{{ route('admin.threats') }}">
                    <i class="bi bi-radar"></i> Monitor Ancaman
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/activity*') ? 'active' : '' }}"
                    href="{{ route('admin.activity') }}">
                    <i class="bi bi-journal-text"></i> Log Aktivitas
                </a>
            </div>
            @endif
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="footer-link"><i class="bi bi-person"></i> Profil</a>
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
            <div class="status-badge">
                <span class="dot-green"></span>
                <span>Sistem Aktif</span>
            </div>
            <div class="dropdown">
                <div class="notif-btn" data-bs-toggle="dropdown" role="button" tabindex="0" title="Notifikasi"
                    aria-label="Notifikasi">
                    <i class="bi bi-bell"></i>
                    @if($navOpenIncidents > 0)
                    <span class="badge-count">{{ $navOpenIncidents }}</span>
                    @endif
                </div>
                <ul class="dropdown-menu dropdown-menu-end notif-menu">
                    <li class="notif-head">
                        <span>Notifikasi</span>
                        <span class="text-muted small">{{ $navOpenIncidents }} insiden terbuka</span>
                    </li>
                    @forelse($navRecentIncidents as $incident)
                    <li>
                        <a class="dropdown-item notif-item" href="{{ route('incidents.show', $incident) }}">
                            <span class="notif-prio {{ $incident->priority }}"></span>
                            <span class="notif-body">
                                <span class="notif-title">{{ $incident->title }}</span>
                                <span class="notif-meta">
                                    {{ $incident->ticket_number }} &middot;
                                    {{ $incident->created_at?->diffForHumans() }}
                                </span>
                            </span>
                        </a>
                    </li>
                    @empty
                    <li>
                        <span class="dropdown-item-text text-muted small">
                            Tidak ada insiden terbuka.
                        </span>
                    </li>
                    @endforelse
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item notif-foot" href="{{ route('incidents.index') }}">
                            Lihat semua insiden
                        </a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <div class="user-menu" data-bs-toggle="dropdown" style="cursor:pointer">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="avatar"
                        style="object-fit: cover; width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border-subtle);">
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ match(auth()->user()->role) {
                        'admin' => 'Administrator',
                        'client' => 'Klien',
                        default => 'Pengguna',
                    } }}</div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i>
                            Profil</a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.api') }}"><i class="bi bi-key"></i> Akses
                            API</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
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
            @hasSection('breadcrumb')
            <nav class="breadcrumb-panel" aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif

            @include('layouts.partials.flash')

            @yield('content')
        </div>
    </div>

    <div class="app-footer">
        <small>SecureOps Platform v2.4.1 &middot; Build 2026.02 &middot; PT Garuda Siber Nusantara</small>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function() {
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.getElementById('sidebar');
        if (toggle && sidebar) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992 && sidebar.classList.contains('open') && !sidebar.contains(e
                        .target)) {
                    sidebar.classList.remove('open');
                }
            });
        }

        // Toast: auto-dismiss after 5s.
        setTimeout(function() {
            document.querySelectorAll('.toast[data-autoclose="1"]').forEach(function(el) {
                el.style.transition = 'opacity .3s ease';
                el.style.opacity = '0';
                setTimeout(function() {
                    el.remove();
                }, 300);
            });
        }, 5000);
    })();
    </script>
    @yield('scripts')
</body>

</html>
