@extends('layouts.app')

@section('title', 'Riwayat Diagnostik')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-clock-history"></i> Riwayat Diagnostik</h3>
        <a href="{{ route('tools.diagnostic') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Kembali ke Diagnostik
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-header">Preset Hostname</div>
        <div class="card-body">
            @forelse($presets as $preset)
                <form method="POST" action="{{ route('tools.diagnostic.run') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="hostname" value="{{ $preset['hostname'] }}">
                    <button type="submit" class="btn btn-outline-secondary btn-sm mb-1">
                        {{ $preset['label'] }}
                        <span class="badge bg-secondary">{{ $preset['type'] }}</span>
                        <code>{{ $preset['hostname'] }}</code>
                    </button>
                </form>
            @empty
                <p class="text-muted mb-0">Belum ada preset.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">Riwayat Eksekusi ({{ $history->total() }} catatan)</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 90px;">Waktu</th>
                            <th style="width: 110px;">Tool</th>
                            <th>Target</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $log)
                            <tr>
                                <td class="text-nowrap">
                                    {{ $log->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td><span class="badge bg-info">{{ $log->tool }}</span></td>
                                <td><code>{{ $log->target }}</code></td>
                                <td>
                                    <pre class="mb-0 small text-break">{{ \Illuminate\Support\Str::limit($log->output, 200) }}</pre>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada riwayat diagnostik.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body">{{ $history->links() }}</div>
    </div>
</div>
@endsection
