@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div>
            <h3 class="page-title"><i class="bi bi-people-fill text-cyan me-2"></i> Manajemen Pengguna</h3>
            <p class="page-subtitle mb-0">Kelola akun administrator, analis keamanan internal, dan hak akses portal klien</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill"></i>
                <span>Buat Pengguna Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Card & Filter -->
    <div class="card">
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama pengguna, email, departemen..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <select name="role" class="form-select">
                            <option value="">Semua Peran (Role)</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User (Internal SOC)</option>
                            <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Klien (Client Portal)</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select name="status" class="form-select">
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Hanya Nonaktif</option>
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('role') || (request()->filled('status') && request('status') !== 'active'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Peran (Role)</th>
                        <th>Status Akun</th>
                        <th>Terdaftar</th>
                        <th class="text-end pe-3" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        $roleBadge = match($user->role) {
                            'admin' => 'bg-danger text-light',
                            'client' => 'bg-info-soft text-cyan border border-subtle',
                            default => 'bg-secondary'
                        };
                        $roleLabel = match($user->role) {
                            'admin' => 'Administrator',
                            'client' => 'Client Portal',
                            default => 'Analis Internal'
                        };
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-secondary font-monospace">#USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar avatar-sm rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 1px solid var(--border-subtle);">
                                <div>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="fw-semibold text-decoration-none text-light hover-cyan d-block">
                                        {{ $user->name }}
                                    </a>
                                    @if($user->department || $user->position)
                                        <small class="text-muted">
                                            {{ $user->position ?: 'Staf' }} {{ $user->department ? '&middot; ' . $user->department : '' }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="small text-decoration-none text-muted hover-cyan d-inline-flex align-items-center gap-2">
                                <i class="bi bi-envelope text-dim"></i> <span>{{ $user->email }}</span>
                            </a>
                        </td>
                        <td>
                            <span class="badge {{ $roleBadge }} rounded-pill px-2.5 py-1">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success d-inline-flex align-items-center gap-1.5 rounded-pill px-2.5 py-1">
                                    <span class="dot-green"></span> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger d-inline-flex align-items-center gap-1.5 rounded-pill px-2.5 py-1">
                                    <span class="dot-red"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted">{{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary" title="Ubah Pengguna">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id() && $user->role !== 'service')
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-secondary" title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <i class="bi {{ $user->is_active ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $user->id }}" title="Reset Password">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Pengguna" onclick="return confirm('Hapus pengguna ini secara permanen?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal Reset Password -->
                            @if($user->id !== auth()->id() && $user->role !== 'service')
                                <div class="modal fade" id="resetPassModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                                                @csrf
                                                <div class="modal-header py-3">
                                                    <h5 class="modal-title d-flex align-items-center gap-2">
                                                        <i class="bi bi-key text-cyan"></i> Reset Password Pengguna
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p class="text-muted small mb-3">
                                                        Atur kata sandi baru untuk akun <strong>{{ $user->name }}</strong> ({{ $user->email }}):
                                                    </p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                                                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" minlength="8" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-3">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-person-x empty-icon text-muted"></i>
                                <div class="empty-title">Belum Ada Pengguna</div>
                                <div class="empty-desc">Tidak ditemukan pengguna yang cocok dengan kriteria filter pencarian Anda.</div>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-person-plus-fill"></i> Buat Pengguna Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
        <div class="card-footer border-top d-flex justify-content-between align-items-center py-3">
            <span class="small text-muted">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari total {{ $users->total() }} pengguna
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection