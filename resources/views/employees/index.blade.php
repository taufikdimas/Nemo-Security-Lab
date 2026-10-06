@extends('layouts.app')

@section('title', 'Direktori Team')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title"><i class="bi bi-people-fill text-cyan me-2"></i> Direktori Team</h3>
            <p class="page-subtitle mb-0">Kelola anggota tim, divisi spesialisasi, penugasan penguji pentest & analis keamanan</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('employees.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Anggota Team</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Main Card & Filter -->
    <div class="card border border-subtle">
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('employees.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama anggota, email, atau jabatan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <select name="department" class="form-select">
                            <option value="">Semua Divisi / Departemen</option>
                            @if(isset($departments))
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('department'))
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 py-3" style="width: 100px;">ID</th>
                        <th class="py-3" style="min-width: 220px;">Nama Anggota Team</th>
                        <th class="py-3" style="width: 180px;">Divisi / Departemen</th>
                        <th class="py-3" style="min-width: 180px;">Jabatan / Peran</th>
                        <th class="py-3" style="min-width: 200px;">Kontak & Email</th>
                        <th class="text-end pe-3 py-3" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td class="ps-3 py-3">
                            <span class="badge bg-secondary font-monospace text-cyan border border-subtle py-1 px-2" style="font-size: 12px;">#TM-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="avatar rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 1px solid var(--border-subtle);">
                                <div>
                                    <a href="{{ route('employees.show', $employee->id) }}" class="fw-semibold text-decoration-none text-light hover-cyan d-block" style="font-size: 14px;">
                                        {{ $employee->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            @if($employee->department)
                                <span class="badge bg-secondary-soft text-light border border-subtle py-1.5 px-2.5 font-sans d-inline-flex align-items-center gap-2" style="font-size: 12.5px;">
                                    <i class="bi bi-diagram-3 text-dim"></i> <span>{{ $employee->department }}</span>
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($employee->position)
                                <span class="text-light fw-medium d-inline-flex align-items-center gap-2" style="font-size: 13px;">
                                    <i class="bi bi-briefcase text-dim"></i> <span>{{ $employee->position }}</span>
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="d-flex flex-column gap-1.5" style="font-size: 13px;">
                                <a href="mailto:{{ $employee->email }}" class="text-decoration-none text-muted hover-cyan d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-envelope text-dim"></i> <span>{{ $employee->email }}</span>
                                </a>
                                @if($employee->phone)
                                    <span class="text-dim d-inline-flex align-items-center gap-2 font-monospace" style="font-size: 12px;">
                                        <i class="bi bi-telephone text-dim"></i> <span>{{ $employee->phone }}</span>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-3 py-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-secondary" title="Detail Anggota Team">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || $employee->created_by === auth()->id())
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-outline-secondary" title="Ubah Data">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Anggota Team" onclick="return confirm('Apakah Anda yakin ingin menghapus data anggota team ini?')">
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
                                <i class="bi bi-people empty-icon text-muted fs-1 d-block mb-2"></i>
                                <div class="empty-title fw-bold text-light fs-5">Belum Ada Anggota Team</div>
                                <div class="empty-desc text-muted mb-3" style="font-size: 14px;">Tidak ditemukan data anggota team yang sesuai dengan pencarian Anda.</div>
                                <a href="{{ route('employees.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-plus-lg"></i> Tambah Anggota Team Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator && $employees->hasPages())
        <div class="card-footer border-top d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <span class="text-muted" style="font-size: 13.5px;">
                Menampilkan {{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }} dari total {{ $employees->total() }} anggota team
            </span>
            <div>
                {{ $employees->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection