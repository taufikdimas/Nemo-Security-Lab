@extends('layouts.app')

@section('title', 'Baca Konten - ' . $file->original_name)

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('files.index') }}" class="text-muted"><i class="bi bi-folder2-open"></i> Berkas</a>
                <span class="sep">/</span>
                <a href="{{ route('files.show', $file) }}" class="text-muted">{{ $file->original_name }}</a>
                <span class="sep">/</span>
                <span class="current">Pratinjau Teks</span>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h3 class="page-title mb-0"><i class="bi bi-file-earmark-text text-cyan me-1"></i> {{ $file->original_name }}</h3>
                <span class="badge bg-secondary font-monospace">{{ $file->humanSize() }}</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('files.download', $file) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-download"></i> Unduh Berkas
            </a>
            <a href="{{ route('files.show', $file) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <span class="text-dim small font-monospace"><i class="bi bi-code-slash me-1"></i> Mode Pembaca Teks Dokumen</span>
            <span class="small text-muted">MIME: {{ $file->mime_type }}</span>
        </div>
        <div class="card-body p-0">
            <div class="p-3 bg-tertiary" style="max-height: 650px; overflow-y: auto;">
                <pre class="mb-0 font-monospace text-light" style="white-space: pre-wrap; word-wrap: break-word; font-size: 13px; line-height: 1.6;">{{ $content }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection