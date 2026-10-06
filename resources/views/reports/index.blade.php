@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-file-earmark-bar-graph"></i> Pusat Laporan</h4>
</div>
<p class="text-muted">Laporan insiden yang sudah diarsipkan tersedia untuk diunduh di bawah ini.</p>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card"><div class="card-header">Laporan Tersedia</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID</th><th>Nama Berkas</th><th>Ukuran</th><th>Diperbarui</th><th></th></tr></thead>
                    <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td>{{ $r['id'] }}</td>
                            <td>{{ $r['filename'] }}</td>
                            <td>{{ number_format($r['size'] / 1024, 1) }} KB</td>
                            <td>{{ $r['modified'] }}</td>
                            <td class="text-end">
                                <a href="{{ route('reports.download', ['report_id' => $r['id'], 'format' => 'txt']) }}"
                                   class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Unduh</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada laporan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card"><div class="card-header">Buat Laporan Insiden</div>
            <div class="card-body">
                <form method="POST" action="{{ route('reports.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Nomor Tiket (ID Insiden)</label>
                        <input type="number" name="report_id" class="form-control @error('report_id') is-invalid @enderror"
                               value="{{ old('report_id') }}" min="1" required>
                        @error('report_id') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                    <div class="mb-3"><label class="form-label">Format</label>
                        <input type="text" name="format" class="form-control" value="{{ old('format', 'txt') }}" maxlength="10"></div>
                    <button class="btn btn-primary w-100"><i class="bi bi-file-earmark-plus"></i> Buat Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
