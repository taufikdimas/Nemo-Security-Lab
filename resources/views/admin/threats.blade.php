@extends('layouts.app')
@section('title', 'Threat Monitor')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-radar"></i> Pemantauan Ancaman</h4>
</div>
<p class="text-muted">Permintaan masuk yang cocok dengan pola pemindaianknown dicatat di sini untuk ditinjau tim SOC.</p>

<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="card stat-card bg-primary-soft"><div class="card-body">
        <div class="fs-4 fw-bold">{{ $stats['total'] }}</div><small>Total Tercatat</small></div></div></div>
    <div class="col-md-4"><div class="card stat-card bg-warning-soft"><div class="card-body">
        <div class="fs-4 fw-bold">{{ $stats['last24h'] }}</div><small>24 Jam Terakhir</small></div></div></div>
    <div class="col-md-4"><div class="card stat-card bg-info-soft"><div class="card-body">
        <div class="fs-4 fw-bold">{{ $stats['sources'] }}</div><small>Sumber Berbeda</small></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-3">
        <div class="card mb-3"><div class="card-header">Severity</div>
            <div class="table-responsive"><table class="table table-sm mb-0">
                @foreach($bySeverity as $row)
                    <tr><td class="text-uppercase">{{ $row->severity }}</td><td class="text-end fw-semibold">{{ $row->total }}</td></tr>
                @endforeach
                @if($bySeverity->isEmpty())<tr><td colspan="2" class="text-muted">Belum ada data.</td></tr>@endif
            </table></div></div>
        <div class="card"><div class="card-header">Path Terbanyak</div>
            <div class="list-group list-group-flush">
                @forelse($topPaths as $t)
                    <div class="list-group-item d-flex justify-content-between"><code class="text-break">{{ $t->path }}</code><span class="badge bg-secondary">{{ $t->total }}</span></div>
                @empty
                    <div class="list-group-item text-muted">Belum ada data.</div>
                @endforelse
            </div></div>
    </div>
    <div class="col-lg-9">
        <div class="card mb-3"><div class="card-body">
            <form method="GET" action="{{ route('admin.threats') }}" class="row g-2 align-items-end">
                <div class="col-md-4"><label class="form-label small mb-1">Cari Path</label>
                    <input type="text" name="q" class="form-control form-control-sm" value="{{ $q }}" placeholder="mis. .env"></div>
                <div class="col-md-4"><label class="form-label small mb-1">Severity</label>
                    <select name="severity" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach(['critical','medium','low'] as $s)
                            <option value="{{ $s }}" @selected($severity===$s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><button class="btn btn-primary btn-sm w-100">Terapkan</button></div>
            </form>
        </div></div>
        <div class="card"><div class="card-header">Log Ancaman</div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Waktu</th><th>Path</th><th>Metode</th><th>IP</th><th>User Agent</th><th>Severity</th></tr></thead>
                    <tbody>
                    @forelse($hits as $h)
                        @php $sc = ['critical'=>'bg-danger','medium'=>'bg-warning','low'=>'bg-secondary'][$h->severity] ?? 'bg-secondary'; @endphp
                        <tr>
                            <td class="text-nowrap">{{ $h->created_at->format('d/m H:i:s') }}</td>
                            <td><code class="text-break">{{ $h->path }}</code></td>
                            <td>{{ $h->method }}</td>
                            <td>{{ $h->ip_address }}</td>
                            <td class="text-truncate small" style="max-width:220px" title="{{ $h->user_agent }}">{{ $h->user_agent }}</td>
                            <td><span class="badge {{ $sc }}">{{ $h->severityLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada aktivitas tercatat.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-body">{{ $hits->links() }}</div>
        </div>
    </div>
</div>
@endsection
