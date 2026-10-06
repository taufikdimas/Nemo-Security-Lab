@extends('layouts.app')

@section('title', 'Detail Proyek - ' . $project->name)

@section('content')
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
    $canManage = auth()->user()->isAdmin() || $project->created_by === auth()->id();
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('projects.index') }}" class="text-muted"><i class="bi bi-kanban"></i> Proyek</a>
                <span class="sep">/</span>
                <span class="current">#PRJ-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <h3 class="page-title mb-0">{{ $project->name }}</h3>
                <span class="badge {{ $statusBadge }} rounded-pill px-3 py-1.5">{{ $statusLabel }}</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($canManage)
                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
            @endif
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Content (Left Column) -->
        <div class="col-lg-8">
            <!-- Project Overview Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-cyan"></i> Ringkasan Proyek
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-dim mb-1">DESKRIPSI</label>
                        <div class="p-3 bg-tertiary rounded text-light" style="line-height: 1.6; min-height: 80px;">
                            {{ $project->description ?: 'Tidak ada deskripsi rinci untuk proyek ini.' }}
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-building me-1"></i> KLIEN TERKAIT</span>
                                <span class="fw-semibold text-light fs-6">{{ $project->client_name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-calendar-range me-1"></i> PERIODE PENGERJAAN</span>
                                <span class="fw-semibold text-light fs-6">
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}
                                    <i class="bi bi-arrow-right text-muted mx-1"></i>
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members Card -->
            <div class="card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-people text-cyan"></i> Anggota Tim ({{ $project->members->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($project->members->count() > 0)
                        <div class="row g-2 mb-3">
                            @foreach($project->members as $member)
                                <div class="col-md-6">
                                    <div class="p-2.5 bg-tertiary rounded d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar avatar-sm">
                                                {{ strtoupper(substr($member->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-light small">{{ $member->name }}</div>
                                                <div class="text-dim" style="font-size: 11px;">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                        @if($canManage)
                                            <form action="{{ route('projects.members.destroy', [$project->id, $member->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Keluarkan Anggota" onclick="return confirm('Hapus anggota ini dari proyek?')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted small mb-3 p-3 bg-tertiary rounded text-center">
                            <i class="bi bi-person-x fs-4 d-block text-dim mb-1"></i>
                            Belum ada anggota yang ditugaskan pada proyek ini.
                        </div>
                    @endif

                    @if($canManage && isset($availableUsers) && $availableUsers->count() > 0)
                        <hr class="my-3">
                        <form action="{{ route('projects.members.store', $project->id) }}" method="POST" class="row g-2 align-items-center">
                            @csrf
                            <div class="col-sm-8">
                                <select name="user_id" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Pilih anggota untuk ditambahkan...</option>
                                    @foreach($availableUsers as $user)
                                        @if(!$project->members->contains($user->id))
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role ?? 'User' }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-person-plus"></i> Tambah Anggota
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Comments & Discussion Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-chat-left-text text-cyan"></i> Catatan & Diskusi Tim ({{ $project->comments->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list mb-4" style="max-height: 450px; overflow-y: auto;">
                        @forelse($project->comments as $comment)
                            <div class="activity-item p-3 mb-2 rounded bg-tertiary">
                                <div class="avatar avatar-sm flex-shrink-0">
                                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="activity-body flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-light">{{ $comment->user->name ?? 'User' }}</span>
                                        <span class="text-dim small">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-primary small" style="white-space: pre-wrap;">{{ $comment->comment }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-chat-dots fs-3 d-block text-dim mb-2"></i>
                                Belum ada catatan atau diskusi. Jadilah yang pertama memberikan update!
                            </div>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('projects.comments.store', $project->id) }}">
                        @csrf
                        <div class="mb-2">
                            <textarea name="comment" class="form-control" placeholder="Tulis catatan kemajuan atau komentar proyek..." rows="3" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5" type="submit">
                                <i class="bi bi-send"></i> Kirim Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Meta Info (Right Column) -->
        <div class="col-lg-4">
            <!-- Quick Status Updater Card -->
            @if($canManage)
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-sliders text-cyan"></i> Perbarui Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('projects.status', $project->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label text-dim">STATUS PROYEK</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="on_hold" {{ $project->status == 'on_hold' ? 'selected' : '' }}>Ditunda (On Hold)</option>
                                    <option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-check-lg"></i> Simpan Status
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Metadata Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-card-checklist text-cyan"></i> Informasi Metadata
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">ID Proyek</span>
                            <span class="font-monospace text-light small">#PRJ-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Pemilik / Pembuat</span>
                            <span class="text-light small fw-semibold">{{ $project->creator->name ?? 'System' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Tanggal Dibuat</span>
                            <span class="text-light small">{{ $project->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Terakhir Diperbarui</span>
                            <span class="text-light small">{{ $project->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection