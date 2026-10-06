@extends('layouts.app')

@section('title', 'Detail Berkas - ' . $file->original_name)

@section('content')
@php
    $canManage = auth()->user()->isAdmin() || $file->uploaded_by === auth()->id();
    $badge = $file->typeBadge();
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('files.index') }}" class="text-muted"><i class="bi bi-folder2-open"></i> Berkas</a>
                <span class="sep">/</span>
                <span class="current">#FIL-{{ str_pad($file->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h3 class="page-title mb-0">{{ $file->original_name }}</h3>
                <span class="badge bg-secondary font-monospace border border-subtle px-2.5 py-1">
                    {{ strtoupper($file->extension()) }}
                </span>
                @if($file->is_public)
                    <span class="badge bg-success rounded-pill px-2.5 py-1">Publik</span>
                @else
                    <span class="badge bg-secondary rounded-pill px-2.5 py-1">Privat</span>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('files.download', $file) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-download"></i> Unduh Berkas
            </a>
            @if($file->isTextFile())
                <a href="{{ route('files.view', $file) }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-file-earmark-text"></i> Baca Konten
                </a>
            @endif
            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
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
                        <i class="bi bi-card-text text-cyan"></i> Deskripsi & Informasi Berkas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-tertiary rounded text-light mb-4" style="line-height: 1.6; min-height: 80px;">
                        {{ $file->description ?: 'Tidak ada catatan atau deskripsi tambahan untuk berkas ini.' }}
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-hdd me-1"></i> UKURAN BERKAS</span>
                                <span class="fw-semibold text-light fs-6 font-monospace">{{ $file->humanSize() }} ({{ number_format($file->file_size) }} bytes)</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1"><i class="bi bi-filetype-raw me-1"></i> TIPE MIME</span>
                                <span class="fw-semibold text-cyan fs-6 font-monospace">{{ $file->mime_type }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Notice / Quick Action -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge text-cyan"></i> Akses & Tindakan Cepat
                    </h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="fw-semibold text-light mb-1">Unduh berkas secara langsung ke perangkat</div>
                        <div class="text-muted small">File akan diunduh dengan nama asli: <code>{{ $file->original_name }}</code></div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('files.download', $file) }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-download"></i> Unduh Sekarang
                        </a>
                        @if($file->isTextFile())
                            <a href="{{ route('files.view', $file) }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-eye"></i> Baca Isi Teks
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Meta (Right Column) -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-cyan"></i> Metadata Sistem
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">ID Berkas</span>
                            <span class="font-monospace text-light small">#FIL-{{ str_pad($file->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Pengunggah</span>
                            <span class="text-light small fw-semibold">{{ $file->uploader->name ?? 'System' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Waktu Unggah</span>
                            <span class="text-light small">{{ $file->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Aksesibilitas</span>
                            <span class="badge {{ $file->is_public ? 'bg-success' : 'bg-secondary' }}">{{ $file->is_public ? 'Publik' : 'Privat' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Nama File Penyimpanan</span>
                            <span class="font-monospace text-dim small text-truncate" style="max-width: 180px;">{{ $file->stored_name }}</span>
                        </div>
                    </div>
                </div>
                @if($canManage)
                    <div class="card-footer p-3">
                        <form action="{{ route('files.destroy', $file) }}" method="POST" class="d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5" onclick="return confirm('Apakah Anda yakin ingin menghapus berkas ini?')">
                                <i class="bi bi-trash"></i> Hapus Berkas
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection