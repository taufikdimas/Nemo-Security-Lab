@extends('layouts.app')

@section('title', 'Proyek Keamanan')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div>
            <h3 class="page-title"><i class="bi bi-kanban text-cyan me-2"></i> Proyek Keamanan</h3>
            <p class="page-subtitle mb-0">Kelola dan pantau seluruh proyek audit, asesmen, dan penetration testing</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('projects.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Proyek Baru</span>
            </a>
        </div>
    </div>

    <!-- Main Card & Filter -->
    <div class="card">
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('projects.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama proyek, deskripsi, atau klien..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif (Dalam Pengerjaan)</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>Ditunda (On Hold)</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('status'))
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 80px;">ID</th>
                        <th>Nama Proyek</th>
                        <th>Klien</th>
                        <th>Jadwal / Durasi</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                    @php
                        $statusBadge = match($project->status) {
                            'active' => 'bg-info',
                            'completed' => 'bg-success',
                            'on_hold' => 'bg-warning',
                            'cancelled' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        $statusLabel = match($project->status) {
                            'active' => 'Aktif',
                            'completed' => 'Selesai',
                            'on_hold' => 'Ditunda',
                            'cancelled' => 'Dibatalkan',
                            default => ucfirst($project->status)
                        };
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-secondary font-monospace">#PRJ-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('projects.show', $project->id) }}" class="fw-semibold text-decoration-none text-light hover-cyan d-block">
                                {{ $project->name }}
                            </a>
                            @if($project->description)
                                <small class="text-muted d-inline-block text-truncate" style="max-width: 320px;">
                                    {{ Str::limit($project->description, 60) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($project->client_name)
                                <span class="d-inline-flex align-items-center gap-2 text-light">
                                    <i class="bi bi-building text-muted me-1"></i> {{ $project->client_name }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                @if($project->start_date || $project->end_date)
                                    <span class="text-light">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}</span>
                                    <i class="bi bi-arrow-right text-muted mx-1"></i>
                                    <span class="text-light">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'N/A' }}</span>
                                @else
                                    <span class="text-muted">Tidak ditentukan</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $statusBadge }} rounded-pill px-2.5 py-1">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar avatar-xs">
                                    {{ strtoupper(substr($project->creator->name ?? 'U', 0, 2)) }}
                                </div>
                                <span class="small text-muted">{{ $project->creator->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary" title="Detail Proyek">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || $project->created_by === auth()->id())
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-outline-secondary" title="Ubah Proyek">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Proyek" onclick="return confirm('Hapus proyek ini secara permanen?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-kanban empty-icon text-muted"></i>
                                <div class="empty-title">Belum Ada Proyek</div>
                                <div class="empty-desc">Tidak ditemukan proyek yang cocok dengan kriteria pencarian Anda.</div>
                                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg"></i> Buat Proyek Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator && $projects->hasPages())
        <div class="card-footer border-top d-flex justify-content-between align-items-center py-3">
            <span class="small text-muted">
                Menampilkan {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }} dari total {{ $projects->total() }} proyek
            </span>
            <div>
                {{ $projects->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection