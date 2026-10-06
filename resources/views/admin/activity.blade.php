@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-journal-text"></i> Log Aktivitas</h3>
        <span class="text-muted">{{ $activities->total() }} catatan</span>
    </div>

    <div class="card mb-3">
        <div class="card-header">Filter</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="filter-user" class="form-label mb-1">Pengguna</label>
                    <select name="user_id" id="filter-user" class="form-select form-select-sm">
                        <option value="">Semua pengguna</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-action" class="form-label mb-1">Aksi</label>
                    <select name="action" id="filter-action" class="form-select form-select-sm">
                        <option value="">Semua aksi</option>
                        @foreach($actions as $a)
                            <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-q" class="form-label mb-1">Detail</label>
                    <input type="text" name="q" id="filter-q" class="form-control form-control-sm"
                           value="{{ request('q') }}" placeholder="Cari detail...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Waktu</th>
                            <th style="width: 150px;">Pengguna</th>
                            <th style="width: 160px;">Aksi</th>
                            <th>Detail</th>
                            <th style="width: 140px;">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $log)
                            <tr>
                                <td class="text-nowrap">
                                    {{ $log->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td>{{ $log->user?->name ?? 'Sistem' }}</td>
                                <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                                <td class="small text-break">{{ $log->details }}</td>
                                <td><code class="small">{{ $log->ip_address ?? '-' }}</code></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada aktivitas yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body">{{ $activities->links() }}</div>
    </div>
</div>
@endsection
