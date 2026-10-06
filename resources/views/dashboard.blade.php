@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="page-header">
    <div>
        <h1 class="page-title">Cyber Security Dashboard</h1>
        <p class="page-subtitle">
            Selamat datang kembali, {{ auth()->user()->name }}
            @if(auth()->user()->position)
            &middot; {{ auth()->user()->position }}
            @endif
        </p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-clipboard2-pulse"></i> Lihat Insiden
        </a>
        <a href="{{ route('incidents.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Buka Tiket Insiden
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Insiden Terbuka</span>
            <i class="bi bi-clipboard2-pulse stat-icon" style="color:var(--critical)"></i>
        </div>
        <div class="stat-value">{{ $openIncidents }}</div>
        <div class="stat-sub">
            @if($newIncidentsToday > 0)
            <span class="trend-up"><i class="bi bi-arrow-up-short"></i>{{ $newIncidentsToday }} baru hari ini</span>
            @else
            <span class="text-muted">Tidak ada insiden baru hari ini</span>
            @endif
        </div>
        <div class="stat-breakdown">
            <span class="sev critical">Kritis: {{ $criticalIncidents }}</span>
            <span class="sev high">Tinggi: {{ $highIncidents }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Aset Terkelola</span>
            <i class="bi bi-hdd-network stat-icon" style="color:var(--info)"></i>
        </div>
        <div class="stat-value">{{ $totalAssets }}</div>
        <div class="stat-sub">{{ $onlineAssets }} online / {{ $offlineAssets }} offline</div>
        <div class="stat-bar">
            <div class="bar-fill"
                style="width:{{ $totalAssets > 0 ? round($onlineAssets / $totalAssets * 100) : 0 }}%;background:var(--success)">
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Proyek Aktif</span>
            <i class="bi bi-kanban stat-icon" style="color:var(--accent-purple)"></i>
        </div>
        <div class="stat-value">{{ $activeProjects }}</div>
        <div class="stat-sub">{{ $completedProjects }} proyek selesai</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Basis Kerentanan</span>
            <i class="bi bi-bug stat-icon" style="color:var(--medium)"></i>
        </div>
        <div class="stat-value">{{ $totalVulns }}</div>
        <div class="stat-sub">
            <span class="sev critical">Kritis: {{ $criticalVulns }}</span>
        </div>
    </div>
</div>

<div class="content-grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Insiden Terbaru</h3>
            <a href="{{ route('incidents.index') }}" class="btn-sm-outline">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if($recentIncidents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentIncidents as $inc)
                        <tr>
                            <td class="mono text-muted">#{{ $inc->id }}</td>
                            <td>
                                <a href="{{ route('incidents.show', $inc) }}">{{ $inc->title }}</a>
                            </td>
                            <td>
                                <span class="badge-sev {{ strtolower($inc->priority) }}">{{ $inc->priority }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge-status {{ $inc->status }}">{{ str_replace('_', ' ', $inc->status) }}</span>
                            </td>
                            <td class="text-muted small">{{ $inc->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <div class="empty-title">Tidak Ada Insiden</div>
                <div class="empty-desc">Belum ada insiden yang tercatat di sistem.</div>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-hdd-stack"></i> Inventaris Aset</h3>
            <a href="{{ route('assets.index') }}" class="btn-sm-outline">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if($assetsByType->count() > 0)
            @foreach($assetsByType as $type => $count)
            <div class="breakdown-row">
                <span class="breakdown-label" title="{{ $type }}">{{ $type }}</span>
                <div class="breakdown-bar">
                    <div class="bar-fill accent"
                        style="width:{{ $totalAssets > 0 ? round($count / $totalAssets * 100) : 0 }}%"></div>
                </div>
                <span class="breakdown-count">{{ $count }}</span>
            </div>
            @endforeach
            @else
            <div class="empty-state">
                <i class="bi bi-hdd-network"></i>
                <div class="empty-title">Belum Ada Aset</div>
                <div class="empty-desc">Tambahkan aset pertama untuk mulai memantau infrastruktur.</div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="content-grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-activity"></i> Log Aktivitas</h3>
        </div>
        <div class="card-body">
            @if($recentActivities->count() > 0)
            <div class="activity-list">
                @foreach($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-dot {{ $activity->action }}"></div>
                    <div class="activity-body">
                        <div class="activity-text">{{ $activity->details ?? $activity->action }}</div>
                        <div class="activity-meta">
                            {{ $activity->user?->name ?? 'Sistem' }}
                            @if($activity->ip_address)
                            &middot; <span class="mono">{{ $activity->ip_address }}</span>
                            @endif
                            &middot; {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-journal-text"></i>
                <div class="empty-title">Belum Ada Aktivitas</div>
                <div class="empty-desc">Aktivitas pengguna akan muncul di sini.</div>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-diagram-3"></i> Insiden per Departemen</h3>
        </div>
        <div class="card-body">
            @if($incidentsByDept->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Departemen</th>
                            <th>Terbuka</th>
                            <th>Diproses</th>
                            <th>Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidentsByDept as $dept)
                        <tr>
                            <td>{{ $dept['department'] }}</td>
                            <td><span class="badge-sev critical">{{ $dept['open'] }}</span></td>
                            <td><span class="badge-sev medium">{{ $dept['in_progress'] }}</span></td>
                            <td><span class="badge-sev low">{{ $dept['resolved'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-diagram-3"></i>
                <div class="empty-title">Belum Ada Data Departemen</div>
                <div class="empty-desc">Data muncul setelah insiden terhubung ke aset.</div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="mb-0"><i class="bi bi-folder2-open"></i> Berkas Terbaru</h3>
        <a href="{{ route('files.index') }}" class="btn-sm-outline">Lihat Semua</a>
    </div>
    <div class="card-body">
        @if($recentFiles->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Berkas</th>
                        <th>Tipe</th>
                        <th>Diunggah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentFiles as $file)
                    <tr>
                        <td class="mono">{{ $file->original_name ?? '—' }}</td>
                        <td class="text-muted small">{{ $file->mime_type ?? '—' }}</td>
                        <td class="text-muted small">{{ $file->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-folder2-open"></i>
            <div class="empty-title">Belum Ada Berkas</div>
            <div class="empty-desc">Berkas yang Anda unggah akan muncul di sini.</div>
        </div>
        @endif
    </div>
</div>
@endsection
