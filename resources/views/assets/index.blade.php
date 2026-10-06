@extends('layouts.app')

@section('title', 'Inventaris Aset')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title"><i class="bi bi-hdd-network text-cyan me-2"></i> Inventaris Aset</h3>
            <p class="page-subtitle mb-0">Manajemen perangkat infrastruktur, pemetaan alamat IP, dan pemantauan siklus pemindaian keamanan</p>
        </div>
        <div class="page-header-actions d-flex gap-2">
            <a href="{{ route('assets.export', request()->query()) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-download"></i>
                <span>Ekspor CSV</span>
            </a>
            <a href="{{ route('assets.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Aset Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Main Card with Filter & Table -->
    <div class="card border border-subtle">
        <!-- Filter Header -->
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('assets.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0"
                                   placeholder="Cari hostname, IP address, atau departemen..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <select name="asset_type" class="form-select">
                            <option value="">Semua Tipe</option>
                            @foreach(['server','workstation','firewall','switch','router','access-point','storage','hypervisor'] as $t)
                                <option value="{{ $t }}" @selected(request('asset_type') === $t)>{{ ucfirst(str_replace('-',' ',$t)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <select name="department" class="form-select">
                            <option value="">Semua Departemen</option>
                            @foreach(['SOC','NOC','IT Infrastructure','Compliance','Finance','HR'] as $d)
                                <option value="{{ $d }}" @selected(request('department') === $d)>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <input type="text" name="os_filter" class="form-control"
                               placeholder="Filter OS (mis. Ubuntu)" value="{{ request('os_filter') }}">
                    </div>
                    <div class="col-auto d-flex gap-2 ms-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->hasAny(['q', 'asset_type', 'department', 'os_filter', 'freshness']))
                            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 py-3" style="min-width: 220px;">Hostname</th>
                        <th class="py-3" style="width: 170px;">IP Address</th>
                        <th class="py-3" style="width: 160px;">Tipe Aset</th>
                        <th class="py-3" style="min-width: 180px;">Versi OS</th>
                        <th class="py-3" style="min-width: 170px;">Departemen</th>
                        <th class="pe-3 py-3" style="width: 170px;">Scan Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($assets as $asset)
                    @php
                        $typeLabel = match($asset->asset_type) {
                            'server' => 'Server',
                            'workstation' => 'Workstation',
                            'firewall' => 'Firewall',
                            'switch' => 'Network Switch',
                            'router' => 'Router',
                            'access-point' => 'Access Point',
                            'storage' => 'Storage NAS/SAN',
                            'hypervisor' => 'Hypervisor',
                            default => ucfirst(str_replace('-',' ',$asset->asset_type))
                        };
                    @endphp
                    <tr>
                        <!-- Hostname -->
                        <td class="ps-3 py-3">
                            <a href="{{ route('assets.show', $asset) }}" class="fw-semibold text-light text-decoration-none hover-cyan d-inline-flex align-items-center gap-2.5" style="font-size: 14px;">
                                <i class="bi bi-hdd text-cyan fs-6"></i>
                                <span>{{ $asset->hostname }}</span>
                            </a>
                        </td>

                        <!-- IP Address -->
                        <td class="py-3">
                            <span class="badge bg-secondary font-monospace text-cyan border border-subtle py-1.5 px-2.5" style="font-size: 12.5px;">
                                {{ $asset->ip_address }}
                            </span>
                        </td>

                        <!-- Tipe Aset -->
                        <td class="py-3">
                            <span class="badge bg-secondary-soft text-light border border-subtle py-1.5 px-2.5 font-sans" style="font-size: 12.5px;">
                                {{ $typeLabel }}
                            </span>
                        </td>

                        <!-- Versi OS -->
                        <td class="py-3">
                            <span class="text-light" style="font-size: 13.5px;">
                                {{ $asset->os_version ?: '-' }}
                            </span>
                        </td>

                        <!-- Departemen -->
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2 text-light fw-medium" style="font-size: 13.5px;">
                                <i class="bi bi-building text-dim me-1"></i>
                                <span>{{ $asset->owner_department }}</span>
                            </div>
                        </td>

                        <!-- Scan Terakhir -->
                        <td class="pe-3 py-3">
                            @if($asset->last_scan_date)
                                <div class="text-dim d-flex align-items-center gap-2" style="font-size: 13px;">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <span>{{ $asset->last_scan_date->format('d M Y') }}</span>
                                </div>
                            @else
                                <span class="text-muted italic" style="font-size: 12.5px;">Belum pernah</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-hdd-network empty-icon text-muted fs-1 d-block mb-2"></i>
                                <div class="empty-title fw-bold text-light fs-5">Tidak Ada Aset Ditemukan</div>
                                <div class="empty-desc text-muted mb-3" style="font-size: 14px;">Tidak ditemukan perangkat yang sesuai dengan kriteria filter pencarian.</div>
                                <a href="{{ route('assets.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-plus-lg"></i> Tambah Aset Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Footer -->
        @if($assets instanceof \Illuminate\Pagination\LengthAwarePaginator && $assets->hasPages())
            <div class="card-footer border-top d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
                <span class="text-muted" style="font-size: 13.5px;">
                    Menampilkan {{ $assets->firstItem() ?? 0 }} - {{ $assets->lastItem() ?? 0 }} dari total {{ $assets->total() }} aset
                </span>
                <div>
                    {{ $assets->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
