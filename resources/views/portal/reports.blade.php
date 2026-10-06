@extends('layouts.portal')

@section('title', 'Laporan SOC')

@section('content')
<div class="page-header mb-4">
    <div>
        <h1 class="page-title">Laporan SOC (Security Operations Center)</h1>
        <p class="page-subtitle mb-0">Dokumentasi log dan laporan operasional SOC untuk {{ $client->name }}</p>
    </div>
</div>

@if($reportFiles->isEmpty())
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-file-earmark-text"></i>
                Belum ada laporan yang tersedia.
            </div>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($reportFiles as $report)
            @php
                $bytes = $report['size'];
                $size = $bytes >= 1048576
                    ? round($bytes / 1048576, 1) . ' MB'
                    : ($bytes >= 1024 ? round($bytes / 1024, 1) . ' KB' : $bytes . ' B');
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-file-earmark-text fs-3" style="color:var(--accent-cyan)"></i>
                            <div class="min-w-0">
                                <div class="fw-semibold text-break">{{ $report['name'] }}</div>
                                <div class="text-muted small mt-1">{{ $size }}</div>
                                <div class="text-muted small">{{ date('d M Y H:i', $report['modified']) }}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('portal.reports.content', $report['name']) }}"
                               class="btn-sm-outline">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            <a href="{{ route('portal.reports.download', $report['name']) }}"
                               class="btn-sm-outline">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection