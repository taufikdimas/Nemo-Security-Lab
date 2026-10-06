@extends('layouts.portal')

@section('title', 'Insiden')

@section('content')
<div class="page-header">
    <div>
        <h1>Insiden</h1>
        <p class="page-subtitle">Insiden yang terkait dengan sistem {{ $client->name }}</p>
    </div>
    <div class="page-header-actions">
        <form method="GET" action="{{ route('portal.incidents') }}" class="d-flex gap-2">
            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="priority" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">Semua Prioritas</option>
                @foreach(['critical' => 'Critical', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'] as $value => $label)
                    <option value="{{ $value }}" {{ request('priority') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-primary btn-sm">Filter</button></noscript>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($incidents->isEmpty())
            <div class="empty-state">
                <i class="bi bi-clipboard2-check"></i>
                Tidak ada insiden yang terkait dengan akun Anda.
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Aset Terkait</th>
                            <th>Dilaporkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $incident)
                            <tr>
                                <td class="font-monospace">#{{ $incident->id }}</td>
                                <td>{{ $incident->title }}</td>
                                <td><span class="badge-sev {{ strtolower($incident->priority) }}">{{ $incident->priority }}</span></td>
                                <td><span class="badge-status {{ $incident->status }}">{{ str_replace('_', ' ', $incident->status) }}</span></td>
                                <td class="text-muted">{{ $incident->asset?->hostname ?? '—' }}</td>
                                <td class="text-muted">{{ $incident->created_at?->format('d M Y H:i') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if($incidents->hasPages())
        <div class="card-footer">{{ $incidents->links() }}</div>
    @endif
</div>
@endsection