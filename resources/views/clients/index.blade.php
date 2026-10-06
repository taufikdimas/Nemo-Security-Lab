@extends('layouts.app')

@section('title', 'Klien & Mitra')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div>
            <h3 class="page-title"><i class="bi bi-people text-cyan me-2"></i> Direktori Klien</h3>
            <p class="page-subtitle mb-0">Kelola informasi mitra, perusahaan, dan kontak narahubung</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('clients.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Klien</span>
            </a>
        </div>
    </div>

    <!-- Main Card & Filter -->
    <div class="card">
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('clients.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama klien, email, atau nama perusahaan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-funnel"></i> Cari
                        </button>
                        @if(request()->filled('search'))
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 80px;">ID</th>
                        <th>Nama Kontak / Klien</th>
                        <th>Perusahaan</th>
                        <th>Kontak & Email</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-secondary font-monospace">#CLI-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-sm">
                                    {{ strtoupper(substr($client->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('clients.show', $client->id) }}" class="fw-semibold text-decoration-none text-light hover-cyan">
                                        {{ $client->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($client->company)
                                <span class="badge bg-info-soft text-cyan border border-subtle d-inline-flex align-items-center gap-2 py-1 px-2.5">
                                    <i class="bi bi-building"></i> <span>{{ $client->company }}</span>
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1.5">
                                @if($client->email)
                                    <a href="mailto:{{ $client->email }}" class="small text-decoration-none text-muted hover-cyan d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-envelope text-dim"></i> <span>{{ $client->email }}</span>
                                    </a>
                                @endif
                                @if($client->phone)
                                    <a href="tel:{{ $client->phone }}" class="small text-decoration-none text-muted hover-cyan d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-telephone text-dim"></i> <span>{{ $client->phone }}</span>
                                    </a>
                                @endif
                                @if(!$client->email && !$client->phone)
                                    <span class="text-muted small">Tidak ada kontak</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $client->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-secondary" title="Detail Klien">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || $client->created_by === auth()->id())
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-outline-secondary" title="Ubah Klien">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Klien" onclick="return confirm('Hapus klien ini beserta datanya?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-people empty-icon text-muted"></i>
                                <div class="empty-title">Belum Ada Klien</div>
                                <div class="empty-desc">Tidak ditemukan data klien yang sesuai dengan pencarian Anda.</div>
                                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg"></i> Tambah Klien Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients instanceof \Illuminate\Pagination\LengthAwarePaginator && $clients->hasPages())
        <div class="card-footer border-top d-flex justify-content-between align-items-center py-3">
            <span class="small text-muted">
                Menampilkan {{ $clients->firstItem() ?? 0 }} - {{ $clients->lastItem() ?? 0 }} dari total {{ $clients->total() }} klien
            </span>
            <div>
                {{ $clients->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection