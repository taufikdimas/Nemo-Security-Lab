@extends('layouts.portal')

@section('title', $project->name)

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $project->name }}</h1>
        <p class="page-subtitle">
            <span class="badge-status {{ $project->status }}">{{ str_replace('_', ' ', $project->status) }}</span>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('portal.projects') }}" class="btn-sm-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">Informasi Proyek</div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Klien</div>
                        <div>{{ $client->name }}</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="text-muted small">Mulai</div>
                        <div>{{ $project->start_date?->format('d M Y') ?? '—' }}</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="text-muted small">Selesai</div>
                        <div>{{ $project->end_date?->format('d M Y') ?? '—' }}</div>
                    </div>
                </div>

                <div class="text-muted small mb-1">Progress</div>
                <div class="progress mb-2">
                    <div class="progress-bar" style="width:{{ $project->progressPercent() }}%">{{ $project->progressPercent() }}%</div>
                </div>

                <div class="text-muted small mb-1 mt-3">Deskripsi</div>
                <p class="mb-0">{{ $project->description ?: 'Tidak ada deskripsi untuk proyek ini.' }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Ringkasan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    <span>{{ str_replace('_', ' ', ucfirst($project->status)) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Progress</span>
                    <span>{{ $project->progressPercent() }}%</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Jumlah Update</span>
                    <span>{{ $project->comments->count() }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Dibuat</span>
                    <span>{{ $project->created_at?->format('d M Y') ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Update Log</div>
    <div class="card-body">
        @if($project->comments->isEmpty())
            <div class="empty-state">
                <i class="bi bi-chat-square-text"></i>
                Belum ada update untuk proyek ini.
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($project->comments->sortByDesc('created_at') as $comment)
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0 text-muted small" style="min-width:96px">
                            {{ $comment->created_at?->format('d M Y') }}
                        </div>
                        <div class="flex-grow-1" style="border-left:2px solid var(--border-color); padding-left:1rem;">
                            <div class="fw-semibold">{{ $comment->user?->name ?? 'Tim Internal' }}</div>
                            <div class="text-muted">{{ $comment->created_at?->diffForHumans() }}</div>
                            <div class="mt-1">{{ $comment->comment }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection