@extends('layouts.app')

@section('title', 'Ubah Pengguna - ' . $user->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('admin.users.index') }}" class="text-muted"><i class="bi bi-people-fill"></i> Manajemen Pengguna</a>
                <span class="sep">/</span>
                <span class="current">Ubah #USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-cyan"></i> Ubah Pengguna: {{ $user->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Mohon periksa kembali input Anda:</div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar avatar-lg rounded-circle border border-2 border-cyan shadow-sm" style="width: 56px; height: 56px; object-fit: cover;">
                        <div>
                            <h5 class="mb-0 text-light">{{ $user->name }}</h5>
                            <small class="text-muted">{{ $user->email }} &middot; <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></small>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 p-3 bg-tertiary rounded border border-subtle">
                            <label for="avatar" class="form-label fw-semibold text-cyan d-flex align-items-center gap-1.5">
                                <i class="bi bi-camera"></i> Ganti Foto Profil Pengguna (JPG, PNG, GIF &middot; Maks 2MB)
                            </label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                            <div class="form-text text-dim">Unggah file gambar baru untuk mengganti foto profil akun ini.</div>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       placeholder="Contoh: Dimas Wahyu Pratama" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       placeholder="dimas@company.id" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password Baru (Opsional)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">Peran Akun (Role) <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User (Analis SOC / Internal)</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                                    <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Klien (Akses Client Portal)</option>
                                </select>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                    <div class="form-text text-dim">Role akun sendiri tidak dapat diubah.</div>
                                @endif
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3" id="client_id_wrap" style="display:none">
                            <div class="p-3 bg-tertiary rounded border border-subtle">
                                <label for="client_id" class="form-label fw-semibold text-cyan">Pilih Entitas Klien Terkait <span class="text-danger">*</span></label>
                                <select class="form-select" id="client_id" name="client_id">
                                    <option value="">-- Pilih Klien --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ (string) old('client_id', $user->client_id) === (string) $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} @if($client->company) ({{ $client->company }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-dim">Akun ini akan otomatis terhubung ke Client Portal dari klien terpilih.</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="department" class="form-label fw-semibold">Departemen / Divisi</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department', $user->department) }}" 
                                       placeholder="Contoh: Security Operations">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label fw-semibold">Jabatan / Posisi</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position', $user->position) }}" 
                                       placeholder="Contoh: SOC Analyst L2">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       placeholder="+62 812-3456-7890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status Keaktifan Akun</label>
                                <div class="p-2.5 bg-tertiary rounded border border-subtle">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <label class="form-check-label fw-semibold text-light" for="is_active">
                                            Akun Aktif
                                        </label>
                                        @if($user->id === auth()->id())
                                            <input type="hidden" name="is_active" value="1">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-check2-circle"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var role = document.getElementById('role');
        var wrap = document.getElementById('client_id_wrap');
        if (!role || !wrap) return;

        function sync() {
            wrap.style.display = role.value === 'client' ? 'block' : 'none';
        }

        role.addEventListener('change', sync);
        sync();
    })();
</script>
@endsection