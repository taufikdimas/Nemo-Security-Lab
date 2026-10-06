@extends('layouts.app')

@section('title', 'Detail Klien - ' . $client->name)

@section('content')
@php
    $canManage = auth()->user()->isAdmin() || $client->created_by === auth()->id();
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('clients.index') }}" class="text-muted"><i class="bi bi-people"></i> Klien</a>
                <span class="sep">/</span>
                <span class="current">#CLI-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <h3 class="page-title mb-0">{{ $client->name }}</h3>
                @if($client->company)
                    <span class="badge bg-info-soft text-cyan border border-subtle px-3 py-1.5 rounded-pill">
                        <i class="bi bi-building me-1"></i> {{ $client->company }}
                    </span>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($canManage)
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
            @endif
            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
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
        <!-- Main Profile Info (Left Column) -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge text-cyan"></i> Informasi Profil Klien
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4 mb-4 pb-3 border-bottom">
                        <div class="avatar avatar-lg" style="width: 64px; height: 64px; font-size: 24px; border-radius: 12px;">
                            {{ strtoupper(substr($client->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="mb-1 text-light">{{ $client->name }}</h4>
                            <p class="text-muted mb-0">
                                <i class="bi bi-building me-1"></i> {{ $client->company ?: 'Organisasi Tidak Disebutkan' }}
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded h-100">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-envelope me-1"></i> ALAMAT EMAIL</span>
                                @if($client->email)
                                    <a href="mailto:{{ $client->email }}" class="fw-semibold text-cyan text-decoration-none fs-6">
                                        {{ $client->email }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded h-100">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-telephone me-1"></i> NOMOR TELEPON / HP</span>
                                @if($client->phone)
                                    <a href="tel:{{ $client->phone }}" class="fw-semibold text-cyan text-decoration-none fs-6">
                                        {{ $client->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-geo-alt me-1"></i> ALAMAT KANTOR / LOKASI</span>
                                <div class="text-light" style="line-height: 1.6;">
                                    {{ $client->address ?: 'Alamat belum dilengkapi.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        @if($client->email)
                            <a href="mailto:{{ $client->email }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-send"></i> Kirim Email
                            </a>
                        @endif
                        @if($client->phone)
                            <a href="tel:{{ $client->phone }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-telephone-outbound"></i> Hubungi Telepon
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Sidebar (Right Column) -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-cyan"></i> Informasi Sistem
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">ID Klien</span>
                            <span class="font-monospace text-light small">#CLI-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Didaftarkan Oleh</span>
                            <span class="text-light small fw-semibold">{{ $client->creator->name ?? 'System' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Tanggal Terdaftar</span>
                            <span class="text-light small">{{ $client->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Terakhir Diperbarui</span>
                            <span class="text-light small">{{ $client->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @if($canManage)
                    <div class="card-footer p-3">
                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5" onclick="return confirm('Apakah Anda yakin ingin menghapus data klien ini?')">
                                <i class="bi bi-trash"></i> Hapus Klien
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection