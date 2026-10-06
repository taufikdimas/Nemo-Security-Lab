@extends('layouts.app')

@section('title', 'Detail Kerentanan - ' . ($vuln->cve_id ?: $vuln->name))

@section('content')
@php
    $canManage = auth()->user()->isAdmin() || $vuln->created_by === auth()->id();
    $sevBadge = match($vuln->severity) {
        'critical' => 'badge-sev critical',
        'high' => 'badge-sev high',
        'medium' => 'badge-sev medium',
        'low' => 'badge-sev low',
        default => 'badge-sev muted'
    };
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('vulndb.index') }}" class="text-muted"><i class="bi bi-shield-exclamation"></i> Basis Kerentanan</a>
                <span class="sep">/</span>
                <span class="current">{{ $vuln->cve_id ?: ('#VULN-' . str_pad($vuln->id, 3, '0', STR_PAD_LEFT)) }}</span>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h3 class="page-title mb-0">{{ $vuln->name }}</h3>
                <span class="{{ $sevBadge }} px-3 py-1">{{ strtoupper($vuln->severity) }}</span>
                @if($vuln->cvss_score)
                    <span class="badge bg-secondary font-monospace border border-subtle px-2.5 py-1">
                        CVSS: {{ number_format($vuln->cvss_score, 1) }}
                    </span>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($canManage)
                <a href="{{ route('vulndb.edit', $vuln->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
            @endif
            <a href="{{ route('vulndb.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
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
        <!-- Main Details (Left Column) -->
        <div class="col-lg-8">
            <!-- Description Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-file-text text-cyan"></i> Deskripsi & Analisis Kerentanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-tertiary rounded text-light" style="line-height: 1.7; min-height: 90px; white-space: pre-wrap;">{{ $vuln->description ?: 'Tidak ada deskripsi rinci untuk kerentanan ini.' }}</div>
                </div>
            </div>

            <!-- Affected Systems Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-hdd-network text-cyan"></i> Sistem & Komponen Terdampak
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-tertiary rounded text-light" style="line-height: 1.6;">
                        {{ $vuln->affected_systems ?: 'Informasi sistem terdampak belum ditentukan.' }}
                    </div>
                </div>
            </div>

            <!-- Remediation / Mitigation Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-cyan"></i> Panduan Mitigasi & Remediasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-tertiary rounded text-light" style="line-height: 1.7; white-space: pre-wrap;">{{ $vuln->remediation ?: 'Belum ada instruksi mitigasi spesifik untuk entri ini.' }}</div>
                </div>
            </div>
        </div>

        <!-- Sidebar Meta & Technical Specs (Right Column) -->
        <div class="col-lg-4">
            <!-- Technical Specs Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-cyan"></i> Spesifikasi Teknis
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">CVE ID</span>
                            <span class="font-monospace text-cyan small fw-semibold">{{ $vuln->cve_id ?: '-' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Tingkat Keparahan</span>
                            <span class="{{ $sevBadge }}">{{ ucfirst($vuln->severity) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Skor CVSS v3</span>
                            <span class="font-monospace text-light fw-bold small">{{ $vuln->cvss_score ? number_format($vuln->cvss_score, 1) : '-' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Kategori / Vektor</span>
                            <span class="text-light small">{{ $vuln->category ?: '-' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Tahun Publikasi</span>
                            <span class="text-light small">{{ $vuln->published_year ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-cyan"></i> Informasi Sistem
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">ID Database</span>
                            <span class="font-monospace text-light small">#VULN-{{ str_pad($vuln->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Dicatat Oleh</span>
                            <span class="text-light small fw-semibold">{{ $vuln->creator->name ?? 'System' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Tanggal Ditambahkan</span>
                            <span class="text-light small">{{ $vuln->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Terakhir Diperbarui</span>
                            <span class="text-light small">{{ $vuln->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @if($canManage)
                    <div class="card-footer p-3">
                        <form action="{{ route('vulndb.destroy', $vuln->id) }}" method="POST" class="d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5" onclick="return confirm('Apakah Anda yakin ingin menghapus entri kerentanan ini?')">
                                <i class="bi bi-trash"></i> Hapus Kerentanan
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection