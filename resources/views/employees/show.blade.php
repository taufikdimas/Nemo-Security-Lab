@extends('layouts.app')

@section('title', 'Detail Anggota Team - ' . $employee->name)

@section('content')
@php
    $canManage = auth()->user()->isAdmin() || $employee->created_by === auth()->id();
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('employees.index') }}" class="text-muted"><i class="bi bi-people-fill"></i> Team</a>
                <span class="sep">/</span>
                <span class="current">#TM-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <h3 class="page-title mb-0 text-light">{{ $employee->name }}</h3>
                @if($employee->department)
                    <span class="badge bg-secondary-soft text-cyan border border-subtle px-3 py-1.5 rounded-pill font-sans" style="font-size: 13px;">
                        <i class="bi bi-diagram-3 me-1"></i> {{ $employee->department }}
                    </span>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 px-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            @if($canManage)
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3">
                    <i class="bi bi-pencil-square"></i> Ubah
                </a>
            @endif
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
            <div class="card border border-subtle mb-4">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-person-lines-fill text-cyan"></i> Profil Anggota Team & Peran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4 pb-3 border-bottom">
                        <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="avatar rounded-circle border border-2 border-cyan shadow-sm" style="width: 64px; height: 64px; object-fit: cover;">
                        <div>
                            <h4 class="mb-1 text-light">{{ $employee->name }}</h4>
                            <p class="text-dim mb-0" style="font-size: 13.5px;">
                                <i class="bi bi-briefcase me-1"></i> {{ $employee->position ?: 'Anggota Team / Spesialis' }}
                                @if($employee->department)
                                    &middot; <span class="text-light">{{ $employee->department }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded border border-subtle h-100">
                                <span class="text-dim small d-block mb-1.5"><i class="bi bi-envelope me-1"></i> ALAMAT EMAIL KERJA</span>
                                <a href="mailto:{{ $employee->email }}" class="fw-semibold text-cyan text-decoration-none" style="font-size: 14px;">
                                    {{ $employee->email }}
                                </a>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded border border-subtle h-100">
                                <span class="text-dim small d-block mb-1.5"><i class="bi bi-telephone me-1"></i> NOMOR TELEPON / WHATSAPP</span>
                                @if($employee->phone)
                                    <a href="tel:{{ $employee->phone }}" class="fw-semibold text-light text-decoration-none font-monospace" style="font-size: 14px;">
                                        {{ $employee->phone }}
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 13px;">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded border border-subtle h-100">
                                <span class="text-dim small d-block mb-1.5"><i class="bi bi-diagram-3 me-1"></i> DIVISI / SPESIALISASI</span>
                                <span class="fw-semibold text-light" style="font-size: 14px;">{{ $employee->department ?: 'Umum' }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded border border-subtle h-100">
                                <span class="text-dim small d-block mb-1.5"><i class="bi bi-award me-1"></i> JABATAN / ROLE</span>
                                <span class="fw-semibold text-light" style="font-size: 14px;">{{ $employee->position ?: 'Anggota Team' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <a href="mailto:{{ $employee->email }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-send"></i> Kirim Pesan Email
                        </a>
                        @if($employee->phone)
                            <a href="tel:{{ $employee->phone }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-telephone-outbound"></i> Hubungi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Sidebar (Right Column) -->
        <div class="col-lg-4">
            <div class="card border border-subtle">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-info-circle text-cyan"></i> Informasi Sistem
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-dim small">ID Team</span>
                            <span class="font-monospace text-cyan small">#TM-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-dim small">Didaftarkan Oleh</span>
                            <span class="text-light small fw-semibold">{{ $employee->creator->name ?? 'System' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-dim small">Tanggal Bergabung</span>
                            <span class="text-light small font-monospace">{{ $employee->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-dim small">Terakhir Diperbarui</span>
                            <span class="text-dim small">{{ $employee->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @if($canManage)
                    <div class="card-footer p-3 border-top">
                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 d-inline-flex align-items-center justify-content-center gap-1.5" onclick="return confirm('Apakah Anda yakin ingin menghapus data anggota team ini?')">
                                <i class="bi bi-trash"></i> Hapus Anggota Team
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection