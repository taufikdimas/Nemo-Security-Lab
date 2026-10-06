@extends('layouts.app')

@section('title', 'Basis Kerentanan (Vulnerability DB)')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div>
            <h3 class="page-title"><i class="bi bi-shield-exclamation text-cyan me-2"></i> Basis Data Kerentanan</h3>
            <p class="page-subtitle mb-0">Repositori referensi CVE, kelemahan keamanan, dan panduan remediasi</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('vulndb.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kerentanan</span>
            </a>
        </div>
    </div>

    <!-- Main Card & Filter -->
    <div class="card">
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('vulndb.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama kerentanan, CVE ID, kategori, atau sistem..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select name="severity" class="form-select">
                            <option value="">Semua Tingkat</option>
                            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Kritis (Critical)</option>
                            <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                            <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                            <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @if(isset($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('severity') || request()->filled('category'))
                            <a href="{{ route('vulndb.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 140px;">CVE ID / Kode</th>
                        <th>Nama Kerentanan</th>
                        <th>Tingkat Keparahan</th>
                        <th>Skor CVSS</th>
                        <th>Kategori</th>
                        <th>Tahun Rilis</th>
                        <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vulns as $vuln)
                    @php
                        $sevBadge = match($vuln->severity) {
                            'critical' => 'badge-sev critical',
                            'high' => 'badge-sev high',
                            'medium' => 'badge-sev medium',
                            'low' => 'badge-sev low',
                            default => 'badge-sev muted'
                        };
                    @endphp
                    <tr>
                        <td class="ps-3">
                            @if($vuln->cve_id)
                                <span class="badge bg-secondary font-monospace text-cyan border border-subtle">
                                    {{ $vuln->cve_id }}
                                </span>
                            @else
                                <span class="badge bg-secondary font-monospace">#VULN-{{ str_pad($vuln->id, 3, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('vulndb.show', $vuln->id) }}" class="fw-semibold text-decoration-none text-light hover-cyan d-block">
                                {{ $vuln->name }}
                            </a>
                            @if($vuln->description)
                                <small class="text-muted d-inline-block text-truncate" style="max-width: 360px;">
                                    {{ Str::limit($vuln->description, 60) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="{{ $sevBadge }}">
                                {{ ucfirst($vuln->severity) }}
                            </span>
                        </td>
                        <td>
                            @if($vuln->cvss_score)
                                <span class="fw-semibold font-monospace {{ $vuln->cvss_score >= 9.0 ? 'text-danger' : ($vuln->cvss_score >= 7.0 ? 'text-warning' : 'text-info') }}">
                                    {{ number_format($vuln->cvss_score, 1) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($vuln->category)
                                <span class="badge bg-info-soft text-cyan border border-subtle">
                                    {{ $vuln->category }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted">{{ $vuln->published_year ?? $vuln->created_at->format('Y') }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('vulndb.show', $vuln->id) }}" class="btn btn-outline-secondary" title="Detail Kerentanan">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || $vuln->created_by === auth()->id())
                                    <a href="{{ route('vulndb.edit', $vuln->id) }}" class="btn btn-outline-secondary" title="Ubah Entri">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('vulndb.destroy', $vuln->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Entri" onclick="return confirm('Hapus entri kerentanan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-shield-slash empty-icon text-muted"></i>
                                <div class="empty-title">Belum Ada Data Kerentanan</div>
                                <div class="empty-desc">Tidak ditemukan entri kerentanan yang sesuai dengan filter pencarian Anda.</div>
                                <a href="{{ route('vulndb.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg"></i> Tambah Kerentanan Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vulns instanceof \Illuminate\Pagination\LengthAwarePaginator && $vulns->hasPages())
        <div class="card-footer border-top d-flex justify-content-between align-items-center py-3">
            <span class="small text-muted">
                Menampilkan {{ $vulns->firstItem() ?? 0 }} - {{ $vulns->lastItem() ?? 0 }} dari total {{ $vulns->total() }} entri
            </span>
            <div>
                {{ $vulns->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection