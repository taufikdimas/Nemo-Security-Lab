@extends('layouts.app')

@section('title', 'Detail Insiden - ' . $incident->ticket_number)

@section('content')
@php
    $canManage = auth()->user()->isAdmin() || $incident->reported_by === auth()->id() || $incident->assignees->contains(auth()->id());
    
    $prioBadge = match($incident->priority) {
        'critical' => 'badge-sev critical',
        'high' => 'badge-sev high',
        'medium' => 'badge-sev medium',
        'low' => 'badge-sev low',
        default => 'badge-sev muted'
    };

    $statusBadge = match($incident->status) {
        'open' => 'bg-danger',
        'in_progress' => 'bg-warning',
        'resolved' => 'bg-success',
        default => 'bg-secondary'
    };

    $statusLabel = match($incident->status) {
        'open' => 'Open (Terbuka)',
        'in_progress' => 'In Progress (Dikerjakan)',
        'resolved' => 'Resolved (Selesai)',
        default => ucfirst(str_replace('_', ' ', $incident->status))
    };
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('incidents.index') }}" class="text-muted"><i class="bi bi-clipboard2-pulse"></i> Insiden</a>
                <span class="sep">/</span>
                <span class="current">{{ $incident->ticket_number }}</span>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h3 class="page-title mb-0">{{ $incident->ticket_number }}: {{ $incident->title }}</h3>
                <span class="{{ $prioBadge }} px-2.5 py-1">{{ strtoupper($incident->priority) }}</span>
                <span class="badge {{ $statusBadge }} rounded-pill px-3 py-1.5">{{ $statusLabel }}</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('incidents.edit', $incident) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-pencil"></i> Ubah
            </a>
            <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
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
            <!-- Incident Details Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text text-cyan"></i> Detail & Kronologi Insiden
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-dim mb-1">DESKRIPSI INSIDEN</label>
                        <div class="p-3 bg-tertiary rounded text-light" style="line-height: 1.7; min-height: 90px; white-space: pre-wrap;">{{ $incident->description ?: 'Tidak ada deskripsi rinci untuk insiden ini.' }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-person me-1"></i> DILAPORKAN OLEH</span>
                                <span class="fw-semibold text-light fs-6">{{ $incident->reportedBy?->name ?? 'Sistem / Anonim' }}</span>
                                <div class="text-dim small" style="font-size: 11px;">{{ $incident->reportedBy?->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-calendar-event me-1"></i> WAKTU INSIDEN</span>
                                <span class="fw-semibold text-light fs-6">{{ $incident->created_at->format('d M Y, H:i') }} WIB</span>
                                <div class="text-dim small" style="font-size: 11px;">{{ $incident->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Incident Timeline & Notes Card -->
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-chat-square-dots text-cyan"></i> Catatan Perkembangan & Penanganan ({{ $incident->notes->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list mb-4" style="max-height: 480px; overflow-y: auto;">
                        @forelse($incident->notes as $note)
                            <div class="activity-item p-3 mb-2 rounded bg-tertiary">
                                <div class="avatar avatar-sm flex-shrink-0">
                                    {{ strtoupper(substr($note->author->name ?? 'S', 0, 2)) }}
                                </div>
                                <div class="activity-body flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <span class="fw-semibold text-light">{{ $note->author->name ?? 'Sistem' }}</span>
                                            <span class="badge bg-secondary ms-1 font-monospace" style="font-size: 10px;">{{ ucfirst($note->author->role ?? 'User') }}</span>
                                        </div>
                                        <span class="text-dim small">{{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-primary small" style="white-space: pre-wrap; line-height: 1.6;">{{ $note->note }}</div>
                                    <div class="text-dim mt-1" style="font-size: 10px;">{{ $note->created_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-chat-left-dots fs-3 d-block text-dim mb-2"></i>
                                Belum ada catatan penanganan untuk insiden ini.
                            </div>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('incidents.notes.store', $incident) }}">
                        @csrf
                        <div class="mb-2">
                            <textarea name="note" class="form-control @error('note') is-invalid @enderror" placeholder="Tulis catatan analisis, tindakan mitigasi, atau progres penanganan..." rows="3" required></textarea>
                            @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5" type="submit">
                                <i class="bi bi-send"></i> Tambah Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Meta Info (Right Column) -->
        <div class="col-lg-4">
            <!-- Quick Status Updater Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-cyan"></i> Perbarui Status Tiket
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('incidents.status', $incident) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label text-dim small mb-1">STATUS INSIDEN</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="open" @selected($incident->status === 'open')>🔴 Open (Terbuka)</option>
                                <option value="in_progress" @selected($incident->status === 'in_progress')>🟡 In Progress (Sedang Ditangani)</option>
                                <option value="resolved" @selected($incident->status === 'resolved')>🟢 Resolved (Selesai)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5">
                            <i class="bi bi-check-lg"></i> Perbarui Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Assigned Analysts Card (Multiple Assignees) -->
            <div class="card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-people text-cyan"></i> Analis Ditugaskan ({{ $incident->assignees->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($incident->assignees->count() > 0)
                        <div class="d-flex flex-column gap-2 mb-3">
                            @foreach($incident->assignees as $assignee)
                                <div class="p-2.5 bg-tertiary rounded d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                        <div class="avatar avatar-sm flex-shrink-0" style="width: 32px; height: 32px; font-size: 11px;">
                                            {{ strtoupper(substr($assignee->name, 0, 2)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-light small text-truncate">{{ $assignee->name }}</div>
                                            <div class="text-dim" style="font-size: 11px;">{{ ucfirst($assignee->role ?? 'Analyst') }} &middot; {{ $assignee->email }}</div>
                                        </div>
                                    </div>
                                    <form action="{{ route('incidents.assign', $incident) }}" method="POST" class="d-inline flex-shrink-0 ms-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="user_id" value="{{ $assignee->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus Analis dari Tiket" onclick="return confirm('Hapus penugasan analis ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted small mb-3 p-3 bg-tertiary rounded text-center">
                            <i class="bi bi-person-x fs-4 d-block text-dim mb-1"></i>
                            Belum ada analis yang ditugaskan pada insiden ini.
                        </div>
                    @endif

                    @php
                        $assignedIds = $incident->assignees->pluck('id')->toArray();
                        $unassignedUsers = $users->filter(fn($u) => !in_array($u->id, $assignedIds));
                    @endphp

                    @if($unassignedUsers->count() > 0)
                        <hr class="my-3">
                        <form action="{{ route('incidents.assign', $incident) }}" method="POST" class="row g-2 align-items-center">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="add">
                            <div class="col-8">
                                <select name="user_id" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Pilih analis untuk ditambahkan...</option>
                                    @foreach($unassignedUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role ?? 'Analyst') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-person-plus"></i> Tambah
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Affected Asset Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-hdd-network text-cyan"></i> Aset Terdampak
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($incident->asset)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                                <span class="text-muted small">Hostname</span>
                                <a href="{{ route('assets.show', $incident->asset) }}" class="fw-semibold text-cyan small">
                                    {{ $incident->asset->hostname }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                                <span class="text-muted small">IP Address</span>
                                <span class="font-monospace text-light small">{{ $incident->asset->ip_address ?: '-' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                                <span class="text-muted small">Tipe / Kategori</span>
                                <span class="text-light small">{{ ucfirst($incident->asset->type ?? '-') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                                <span class="text-muted small">Status Aset</span>
                                <span class="badge {{ $incident->asset->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($incident->asset->status ?? 'Active') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="p-3 text-muted small text-center">
                            Tidak ada aset spesifik yang ditautkan.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Incident Metadata Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-cyan"></i> Informasi Metadata
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Nomor Tiket</span>
                            <span class="font-monospace text-cyan small fw-semibold">{{ $incident->ticket_number }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Prioritas</span>
                            <span class="{{ $prioBadge }}">{{ ucfirst($incident->priority) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Status Tiket</span>
                            <span class="badge {{ $statusBadge }}">{{ $incident->statusLabel() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Dibuat Pada</span>
                            <span class="text-light small">{{ $incident->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Terakhir Diperbarui</span>
                            <span class="text-light small">{{ $incident->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Diselesaikan Pada</span>
                            <span class="text-light small">{{ $incident->resolved_at ? $incident->resolved_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-3">
                    <form action="{{ route('incidents.destroy', $incident) }}" method="POST" class="d-inline w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket insiden ini?')">
                            <i class="bi bi-trash"></i> Hapus Insiden
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
