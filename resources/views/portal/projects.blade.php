@extends('layouts.portal')

@section('title', 'Proyek Saya')

@section('content')
<div class="page-header">
    <div>
        <h1>Proyek Saya</h1>
        <p class="page-subtitle">Seluruh proyek {{ $client->name }} di SecureOps</p>
    </div>
    <div class="page-header-actions">
        <form method="GET" action="{{ route('portal.projects') }}" class="d-flex gap-2">
            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['active' => 'Active', 'completed' => 'Completed', 'on_hold' => 'On Hold'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-primary btn-sm">Filter</button></noscript>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($projects->isEmpty())
            <div class="empty-state">
                <i class="bi bi-kanban"></i>
                Belum ada proyek yang ditugaskan
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nama Proyek</th>
                            <th>Klien</th>
                            <th>Status</th>
                            <th style="min-width:140px">Progress</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td class="text-muted">{{ $project->client_name }}</td>
                                <td><span class="badge-status {{ $project->status }}">{{ str_replace('_', ' ', $project->status) }}</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:{{ $project->progressPercent() }}%">{{ $project->progressPercent() }}%</div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $project->start_date?->format('d M Y') ?? '—' }}</td>
                                <td class="text-muted">{{ $project->end_date?->format('d M Y') ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('portal.projects.show', $project) }}" class="btn-sm-outline">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if($projects->hasPages())
        <div class="card-footer">{{ $projects->links() }}</div>
    @endif
</div>
@endsection