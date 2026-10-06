@extends('layouts.app')
@section('title', 'Network Diagnostic')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-broadcast-pin"></i> Pemeriksaan Kesehatan Jaringan Internal</h4>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
</div>
<p class="text-muted">Gunakan halaman ini untuk memverifikasi resolusi nama host pada aset dan layanan di jaringan internal.</p>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card"><div class="card-header">Parameter Resolusi</div>
            <div class="card-body">
                <form method="POST" action="{{ route('tools.diagnostic.run') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Hostname</label>
                        <input type="text" name="hostname" class="form-control @error('hostname') is-invalid @enderror"
                               value="{{ old('hostname', $hostname) }}" placeholder="mis. srv-backup-01.garuda-siber.internal" required>
                        @error('hostname') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                    <div class="mb-3"><label class="form-label">Jenis Record</label>
                        <select name="type" class="form-select">
                            @foreach(['A','AAAA','MX','NS','TXT','CNAME','SOA','SRV','PTR','CAA'] as $t)
                                <option value="{{ $t }}" @selected(old('type', $type)===$t)>{{ $t }}</option>
                            @endforeach
                        </select></div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i> Resolusi</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card"><div class="card-header">Hasil</div>
            <div class="card-body">
                @if($records)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Jenis</th><th>Nilai</th><th>TTL</th></tr></thead>
                            <tbody>
                            @foreach($records as $r)
                                <tr>
                                    <td>{{ $r['type'] ?? 'A' }}</td>
                                    <td class="text-break">{{ $r['ip'] ?? ($r['target'] ?? ($r['data'] ?? ($r['host'] ?? json_encode($r)))) }}</td>
                                    <td>{{ $r['ttl'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif($result)
                    <pre class="bg-light p-3 mb-0 text-break">{{ $result }}</pre>
                @else
                    <p class="text-muted mb-0">Belum ada hasil. Jalankan resolusi untuk melihat data.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
