@extends('layouts.app')

@section('title', 'Buat Pengguna Baru')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('admin.users.index') }}" class="text-muted"><i class="bi bi-people-fill"></i> Manajemen Pengguna</a>
                <span class="sep">/</span>
                <span class="current">Buat Pengguna Baru</span>
            </div>

            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-person-plus text-cyan"></i> Buat Pengguna Baru
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

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Contoh: Dimas Wahyu Pratama" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="dimas@company.id" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password Awal <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">Peran Akun (Role) <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User (Analis SOC / Internal)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Klien (Akses Client Portal)</option>
                                </select>
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
                                        <option value="{{ $client->id }}" {{ (string) old('client_id') === (string) $client->id ? 'selected' : '' }}>
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
                                       id="department" name="department" value="{{ old('department') }}" 
                                       placeholder="Contoh: Security Operations">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label fw-semibold">Jabatan / Posisi</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position') }}" 
                                       placeholder="Contoh: SOC Analyst L2">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+62 812-3456-7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-check2-circle"></i> Buat Pengguna
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