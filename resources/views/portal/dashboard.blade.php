@extends('layouts.portal')

@section('title', 'Dashboard Portal Klien')

@section('content')
<div class="page-header mb-4">
    <div>
        <h1 class="page-title">{{ $client->name }}</h1>
        <p class="page-subtitle mb-0">Ringkasan status keamanan, kemajuan proyek, dan pemantauan insiden aktif</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('portal.projects') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-kanban me-1"></i> Proyek Saya
        </a>
        <a href="{{ route('portal.incidents') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-clipboard2-pulse me-1"></i> Pantau Insiden
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-primary-soft">
            <div class="card-body">
                <div class="stat-header">
                    <span class="stat-label">Total Proyek</span>
                    <i class="bi bi-kanban stat-icon" style="color:var(--accent-cyan)"></i>
                </div>
                <div class="stat-value">{{ $totalProjects }}</div>
                <div class="stat-sub">Seluruh proyek terdaftar</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card bg-warning-soft">
            <div class="card-body">
                <div class="stat-header">
                    <span class="stat-label">Proyek Aktif</span>
                    <i class="bi bi-play-circle stat-icon" style="color:var(--warning)"></i>
                </div>
                <div class="stat-value">{{ $activeProjects->count() }}</div>
                <div class="stat-sub">Sedang tahap pengerjaan</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card bg-success-soft">
            <div class="card-body">
                <div class="stat-header">
                    <span class="stat-label">Proyek Selesai</span>
                    <i class="bi bi-check-circle stat-icon" style="color:var(--success)"></i>
                </div>
                <div class="stat-value">{{ $completedProjects }}</div>
                <div class="stat-sub">Telah selesai dieksekusi</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg, rgba(239,68,68,0.18), rgba(220,38,38,0.12)) !important;">
            <div class="card-body">
                <div class="stat-header">
                    <span class="stat-label">Insiden Terbuka</span>
                    <i class="bi bi-clipboard2-pulse stat-icon" style="color:var(--danger)"></i>
                </div>
                <div class="stat-value">{{ $openIncidents }}</div>
                <div class="stat-sub">Open &amp; sedang ditangani</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Projects and Incidents -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold text-light"><i class="bi bi-kanban text-cyan me-1.5"></i> Proyek Terbaru</span>
                <a href="{{ route('portal.projects') }}" class="btn-sm-outline text-decoration-none">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentProjects->isEmpty())
                    <div class="empty-state py-5 text-center">
                        <i class="bi bi-kanban fs-1 text-dim d-block mb-2"></i>
                        <span class="text-muted">Belum ada proyek yang ditugaskan untuk organisasi Anda.</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Nama Proyek</th>
                                    <th>Status</th>
                                    <th style="min-width:130px">Progress</th>
                                    <th>Mulai</th>
                                    <th class="pe-3">Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProjects as $project)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('portal.projects.show', $project) }}" class="fw-medium text-light text-decoration-none hover-cyan">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge-status {{ $project->status }}">
                                                {{ str_replace('_', ' ', $project->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 14px;">
                                                <div class="progress-bar" style="width:{{ $project->progressPercent() }}%; font-size: 10px; font-weight: bold;">
                                                    {{ $project->progressPercent() }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-dim small">{{ $project->start_date?->format('d M Y') ?? '—' }}</td>
                                        <td class="text-dim small pe-3">{{ $project->end_date?->format('d M Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold text-light"><i class="bi bi-clipboard2-pulse text-cyan me-1.5"></i> Insiden Terbaru</span>
                <a href="{{ route('portal.incidents') }}" class="btn-sm-outline text-decoration-none">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentIncidents->isEmpty())
                    <div class="empty-state py-5 text-center">
                        <i class="bi bi-clipboard2-check fs-1 text-dim d-block mb-2"></i>
                        <span class="text-muted">Tidak ada insiden aktif yang memerlukan perhatian saat ini.</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Judul</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th class="pe-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentIncidents as $incident)
                                    <tr>
                                        <td class="ps-3 font-monospace text-cyan small">#{{ $incident->id }}</td>
                                        <td>
                                            <span class="text-light fw-medium small text-truncate d-inline-block" style="max-width: 140px;" title="{{ $incident->title }}">
                                                {{ $incident->title }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-sev {{ strtolower($incident->priority) }} px-2 py-0.5" style="font-size: 10px;">
                                                {{ strtoupper($incident->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-status {{ $incident->status }}">
                                                {{ str_replace('_', ' ', $incident->status) }}
                                            </span>
                                        </td>
                                        <td class="text-dim small pe-3">{{ $incident->created_at?->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection