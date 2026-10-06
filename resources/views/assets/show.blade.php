@extends('layouts.app')

@section('title', $asset->hostname . ' - Detail Aset')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Top Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('assets.index') }}" class="text-muted"><i class="bi bi-hdd-network"></i> Aset</a>
                <span class="sep">/</span>
                <span class="current">{{ $asset->hostname }}</span>
            </div>
            <div class="d-flex align-items-center gap-2.5 flex-wrap">
                <span class="badge bg-secondary font-monospace text-cyan border border-subtle px-2.5 py-1.5 fs-6">
                    <i class="bi bi-hdd me-1"></i> {{ $asset->hostname }}
                </span>
                <h3 class="page-title mb-0 text-light">{{ $asset->ip_address }}</h3>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('incidents.create') }}?asset_id={{ $asset->id }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3">
                <i class="bi bi-plus-lg"></i> Buka Tiket Insiden
            </a>
            @if(auth()->user()->isAdmin() || $asset->created_by === auth()->id())
                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3">
                    <i class="bi bi-pencil-square"></i> Ubah
                </a>
                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3" onclick="return confirm('Apakah Anda yakin ingin menghapus data aset ini secara permanen?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @php
        $typeLabel = match($asset->asset_type) {
            'server' => 'Server',
            'workstation' => 'Workstation',
            'firewall' => 'Firewall',
            'switch' => 'Network Switch',
            'router' => 'Router',
            'access-point' => 'Access Point',
            'storage' => 'Storage NAS/SAN',
            'hypervisor' => 'Hypervisor',
            default => ucfirst(str_replace('-',' ',$asset->asset_type))
        };
    @endphp

    <!-- Overview Bar -->
    <div class="card mb-4 border border-subtle">
        <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2.5 flex-wrap">
                <span class="badge bg-secondary-soft text-light border border-subtle py-1.5 px-3 font-sans" style="font-size: 13px;">
                    <i class="bi bi-tag me-1 text-dim"></i> {{ $typeLabel }}
                </span>
                <span class="badge bg-secondary-soft text-light border border-subtle py-1.5 px-3 font-sans" style="font-size: 13px;">
                    <i class="bi bi-building me-1 text-dim"></i> {{ $asset->owner_department }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-dim small">Scan Terakhir:</span>
                @if($asset->last_scan_date)
                    <span class="badge bg-success rounded-pill px-2.5 py-1" style="font-size: 12px;">
                        {{ $asset->last_scan_date->format('d M Y') }}
                    </span>
                @else
                    <span class="badge bg-secondary rounded-pill px-2.5 py-1" style="font-size: 12px;">
                        Belum pernah dipindai
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Column (Left) -->
        <div class="col-lg-7">
            <!-- Informasi Spesifikasi Aset -->
            <div class="card mb-4 border border-subtle">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-hdd-network text-cyan"></i> Spesifikasi & Jaringan
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="width: 190px; font-size: 13px;">Hostname</th>
                                    <td class="pe-3 py-2.5 font-monospace text-cyan fw-bold" style="font-size: 13.5px;">{{ $asset->hostname }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="font-size: 13px;">Alamat IP</th>
                                    <td class="pe-3 py-2.5 font-monospace text-light" style="font-size: 13.5px;">{{ $asset->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="font-size: 13px;">Tipe Perangkat</th>
                                    <td class="pe-3 py-2.5 text-light" style="font-size: 13.5px;">{{ $typeLabel }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="font-size: 13px;">Versi Sistem Operasi</th>
                                    <td class="pe-3 py-2.5 text-light" style="font-size: 13.5px;">{{ $asset->os_version ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="font-size: 13px;">Departemen Pemilik</th>
                                    <td class="pe-3 py-2.5 text-light" style="font-size: 13.5px;">{{ $asset->owner_department }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="font-size: 13px;">Ditambahkan Oleh</th>
                                    <td class="pe-3 py-2.5 text-light" style="font-size: 13.5px;">{{ $asset->createdBy?->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-2.5 text-dim" style="font-size: 13px;">Terdaftar Pada</th>
                                    <td class="pe-3 py-2.5 text-dim font-monospace" style="font-size: 13px;">{{ $asset->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Catatan Inventaris -->
            <div class="card border border-subtle">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-card-text text-cyan"></i> Catatan Inventaris
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 rounded bg-tertiary border border-subtle text-light" style="font-size: 13.5px; line-height: 1.6; min-height: 80px;">
                        {{ $asset->notes ?: 'Tidak ada catatan inventaris tambahan untuk perangkat ini.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column (Right) -->
        <div class="col-lg-5">
            <!-- Insiden Terkait Card -->
            <div class="card border border-subtle mb-4">
                <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-clipboard2-pulse text-cyan"></i> Insiden Terkait
                        <span class="badge bg-tertiary text-cyan border border-subtle ms-1" style="font-size: 12px;">{{ $incidents->count() }}</span>
                    </h5>
                    <a href="{{ route('incidents.create') }}?asset_id={{ $asset->id }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 px-2.5">
                        <i class="bi bi-plus"></i> Buka Tiket
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($incidents as $inc)
                        @php
                            $prioBadge = match(strtolower($inc->priority)) {
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
                        <div class="p-3 border-bottom border-subtle d-flex align-items-center justify-content-between gap-2 hover-bg-tertiary">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <a href="{{ route('incidents.show', $inc) }}" class="badge bg-secondary font-monospace text-cyan border border-subtle text-decoration-none py-0.5 px-1.5" style="font-size: 11px;">
                                        {{ $inc->ticket_number ?? '#' . $inc->id }}
                                    </a>
                                    <span class="{{ $prioBadge }} px-1.5 py-0.5" style="font-size: 10px;">
                                        {{ strtoupper($inc->priority) }}
                                    </span>
                                    <span class="badge {{ $statusBadge }} rounded-pill px-2 py-0.5" style="font-size: 10px;">
                                        {{ ucwords(str_replace('_', ' ', $inc->status)) }}
                                    </span>
                                </div>
                                <a href="{{ route('incidents.show', $inc) }}" class="fw-semibold text-light text-decoration-none hover-cyan d-block" style="font-size: 13.5px;">
                                    {{ Str::limit($inc->title, 35) }}
                                </a>
                                <span class="text-dim" style="font-size: 11.5px;">
                                    {{ $inc->created_at?->diffForHumans() ?? '-' }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('incidents.show', $inc) }}" class="btn btn-sm btn-outline-secondary" title="Detail Insiden">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-dim">
                            <i class="bi bi-clipboard2-check fs-3 d-block mb-1 text-muted"></i>
                            <span style="font-size: 13.5px;">Belum ada tiket insiden yang tercatat untuk aset ini.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
