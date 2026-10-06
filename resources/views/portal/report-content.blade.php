@extends('layouts.portal')

@section('title', $name)

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $name }}</h1>
        <p class="page-subtitle">Laporan engagement untuk {{ $client->name }}</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('portal.reports.download', $name) }}" class="btn-sm-outline">
            <i class="bi bi-download"></i> Unduh
        </a>
        <a href="{{ route('portal.reports') }}" class="btn-sm-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-4">
        <div class="card h-100">
            <div class="card-header">Informasi Berkas</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Nama</span>
                    <span class="text-break text-end">{{ $name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ukuran</span>
                    <span>{{ number_format($size) }} B</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Diubah</span>
                    <span>{{ date('d M Y H:i', $modified) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Isi Laporan</div>
    <div class="card-body">
        {{-- Dibungkus <pre> dan di-escape oleh Blade: isi berkas laporan tidak
             pernah diperlakukan sebagai HTML. --}}
        <pre class="mb-0"><code>{{ $content }}</code></pre>
    </div>
</div>
@endsection