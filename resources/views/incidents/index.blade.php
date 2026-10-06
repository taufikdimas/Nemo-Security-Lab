@extends('layouts.app')

@section('title', 'Manajemen Insiden')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div>
            <h3 class="page-title"><i class="bi bi-clipboard2-pulse text-cyan me-2"></i> Manajemen Insiden Keamanan</h3>
            <p class="page-subtitle mb-0">Pelacakan, penanganan respon insiden, dan koordinasi tim analis SOC</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('incidents.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Buka Tiket Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Main Card with Filter & Table -->
    <div class="card">
        <!-- Filter Header -->
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('incidents.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0"
                                   placeholder="Cari nomor tiket, judul insiden, atau deskripsi..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="open" @selected(request('status') === 'open')>Open (Terbuka)</option>
                            <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress (Dikerjakan)</option>
                            <option value="resolved" @selected(request('status') === 'resolved')>Resolved (Selesai)</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <select name="priority" class="form-select">
                            <option value="">Semua Prioritas</option>
                            <option value="critical" @selected(request('priority') === 'critical')>Critical (Kritis)</option>
                            <option value="high" @selected(request('priority') === 'high')>High (Tinggi)</option>
                            <option value="medium" @selected(request('priority') === 'medium')>Medium (Sedang)</option>
                            <option value="low" @selected(request('priority') === 'low')>Low (Rendah)</option>
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-1.5 ms-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->hasAny(['q', 'status', 'priority']))
                            <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 140px;">Nomor Tiket</th>
                        <th style="min-width: 260px;">Judul Insiden</th>
                        <th style="width: 110px;">Prioritas</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 180px;">Aset Terdampak</th>
                        <th style="min-width: 200px;">Analis Ditugaskan</th>
                        <th class="text-end pe-3" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($incidents as $inc)
                    @php
                        $prioBadge = match($inc->priority) {
                            'critical' => 'badge-sev critical',
                            'high' => 'badge-sev high',
                            'medium' => 'badge-sev medium',
                            'low' => 'badge-sev low',
                            default => 'badge-sev muted'
                        };

                        $statusBadge = match($inc->status) {
                            'open' => 'bg-danger',
                            'in_progress' => 'bg-warning text-dark',
                            'resolved' => 'bg-success',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <tr>
                        <!-- Nomor Tiket -->
                        <td class="ps-3">
                            <a href="{{ route('incidents.show', $inc) }}" class="badge bg-secondary font-monospace text-cyan border border-subtle text-decoration-none py-1.5 px-2">
                                {{ $inc->ticket_number }}
                            </a>
                        </td>

                        <!-- Judul Insiden -->
                        <td>
                            <a href="{{ route('incidents.show', $inc) }}" class="fw-semibold text-light text-decoration-none hover-cyan d-block">
                                {{ $inc->title }}
                            </a>
                            <div class="d-flex align-items-center gap-2.5 text-dim small mt-0.5">
                                <span><i class="bi bi-clock me-1.5"></i>{{ $inc->created_at->diffForHumans() }}</span>
                                @if($inc->reportedBy)
                                    <span>&middot;</span>
                                    <span><i class="bi bi-person me-1.5"></i>{{ $inc->reportedBy->name }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Prioritas -->
                        <td>
                            <span class="{{ $prioBadge }} px-2 py-0.5">
                                {{ strtoupper($inc->priority) }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td>
                            <span class="badge {{ $statusBadge }} rounded-pill px-2.5 py-1">
                                {{ $inc->statusLabel() }}
                            </span>
                        </td>

                        <!-- Aset Terdampak -->
                        <td>
                            @if($inc->asset)
                                <a href="{{ route('assets.show', $inc->asset) }}" class="text-light text-decoration-none hover-cyan small fw-medium d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-hdd text-cyan"></i>
                                    <span>{{ $inc->asset->hostname }}</span>
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        <!-- Tim Analis Ditugaskan -->
                        <td>
                            @if($inc->assignees->isNotEmpty())
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    @foreach($inc->assignees->take(2) as $assignee)
                                        <span class="badge bg-secondary-soft text-light border border-subtle d-inline-flex align-items-center gap-2 py-1 px-2.5 font-sans" title="{{ $assignee->email }} ({{ ucfirst($assignee->role ?? 'Analyst') }})">
                                            <span class="avatar avatar-xs" style="width: 20px; height: 20px; font-size: 10px;">
                                                {{ strtoupper(substr($assignee->name, 0, 2)) }}
                                            </span>
                                            <span class="small">{{ Str::limit($assignee->name, 12) }}</span>
                                        </span>
                                    @endforeach
                                    @if($inc->assignees->count() > 2)
                                        <span class="badge bg-tertiary text-cyan border border-subtle px-1.5 py-1 font-monospace" title="dan {{ $inc->assignees->count() - 2 }} analis lainnya">
                                            +{{ $inc->assignees->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            @elseif($inc->assignedTo)
                                <span class="badge bg-secondary-soft text-light border border-subtle py-1 px-2 font-sans">
                                    {{ $inc->assignedTo->name }}
                                </span>
                            @else
                                <span class="text-muted small italic">Belum ditugaskan</span>
                            @endif
                        </td>

                        <!-- Tombol Aksi -->
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('incidents.show', $inc) }}" class="btn btn-outline-secondary" title="Detail Insiden">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('incidents.edit', $inc) }}" class="btn btn-outline-secondary" title="Ubah Insiden">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('incidents.destroy', $inc) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus Insiden" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket insiden ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-clipboard2-check empty-icon text-muted fs-1 d-block mb-2"></i>
                                <div class="empty-title fw-bold text-light">Tidak Ada Tiket Insiden</div>
                                <div class="empty-desc text-muted mb-3">Tidak ditemukan data insiden yang sesuai dengan filter pencarian.</div>
                                <a href="{{ route('incidents.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-plus-lg"></i> Buka Tiket Insiden Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Footer -->
        @if($incidents instanceof \Illuminate\Pagination\LengthAwarePaginator && $incidents->hasPages())
            <div class="card-footer border-top d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
                <span class="small text-muted">
                    Menampilkan {{ $incidents->firstItem() ?? 0 }} - {{ $incidents->lastItem() ?? 0 }} dari total {{ $incidents->total() }} insiden
                </span>
                <div>
                    {{ $incidents->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
