@extends('layouts.app')

@section('title', 'Profil Akun')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title"><i class="bi bi-person-gear text-cyan me-2"></i> Profil & Pengaturan Akun</h3>
            <p class="page-subtitle mb-0">Kelola identitas, foto profil, keamanan kata sandi, dan kredensial sistem Anda</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('profile.api') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-key"></i> Kredensial API
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
        <div class="alert alert-danger mb-4">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan validasi:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Form (Left Column) -->
        <div class="col-lg-7">
            <!-- Avatar Upload Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-camera text-cyan"></i> Foto Profil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div class="flex-shrink-0">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                 class="rounded-circle border border-2 border-cyan shadow-sm" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                                @csrf
                                <label for="avatar" class="form-label text-dim small mb-1">UNGGAH FOTO PROFIL BARU (JPG, PNG, GIF, WEBP &middot; MAKS 5MB)</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-upload"></i> Unggah
                                    </button>
                                </div>
                                <span class="text-dim" style="font-size: 11px;">Foto akan langsung diperbarui di topbar navigasi dan sistem.</span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details Form -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-person-lines-fill text-cyan"></i> Informasi Data Diri & Keamanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="department" class="form-label fw-semibold">Departemen / Divisi</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" 
                                       value="{{ old('department', $user->department) }}" placeholder="Contoh: Security Operations Center">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label fw-semibold">Jabatan / Posisi</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" 
                                       value="{{ old('position', $user->position) }}" placeholder="Contoh: Senior Security Analyst">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" 
                                       value="{{ old('phone', $user->phone) }}" placeholder="+62 812-3456-7890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label fw-semibold">Alamat Kantor / Domisili</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" 
                                       value="{{ old('address', $user->address) }}" placeholder="Gedung Cyber Lt. 5">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="bio" class="form-label fw-semibold">Bio / Catatan Diri</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" 
                                      rows="3" placeholder="Informasi singkat atau catatan operasional Anda...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Password Change Section -->
                        <h6 class="fw-semibold text-light mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-key text-cyan"></i> Ganti Password Akun (Opsional)
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                                       placeholder="Minimal 6 karakter" minlength="6">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Ulangi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                       placeholder="Konfirmasi password baru">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end pt-2 border-top">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-check2-circle"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Meta Info (Right Column) -->
        <div class="col-lg-5">
            <!-- Account Info Card -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-cyan"></i> Informasi Akun Sistem
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">User ID</span>
                            <span class="badge bg-secondary font-monospace">#USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Peran (Role)</span>
                            <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }} rounded-pill px-2.5 py-1">
                                {{ $user->isAdmin() ? 'Administrator' : 'Analis Internal' }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Status Akun</span>
                            <span class="badge bg-success d-inline-flex align-items-center gap-1 rounded-pill px-2.5 py-1">
                                <span class="dot-green"></span> Aktif
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Terdaftar Sejak</span>
                            <span class="text-light small">{{ $user->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                            <span class="text-muted small">Terakhir Diperbarui</span>
                            <span class="text-light small">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Token Card -->
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-cyan"></i> Kredensial API Token
                    </h6>
                    <a href="{{ route('profile.api') }}" class="btn-sm-outline">
                        Dokumentasi
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="alert alert-warning py-2 px-3 mb-3 small d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-5"></i>
                        <div>Token ini digunakan untuk otentikasi REST API feed aset dan insiden.</div>
                    </div>

                    <div class="input-group">
                        <input type="text" class="form-control font-monospace" id="apiToken" 
                               value="{{ $user->api_token }}" readonly>
                        <button class="btn btn-outline-primary d-inline-flex align-items-center gap-1" type="button" id="copyToken">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                    <div class="form-text text-success mt-2" id="copyStatus" role="status" aria-live="polite"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var copyBtn = document.getElementById('copyToken');
        var tokenInput = document.getElementById('apiToken');
        var statusMsg = document.getElementById('copyStatus');

        if (copyBtn && tokenInput) {
            copyBtn.addEventListener('click', function () {
                navigator.clipboard.writeText(tokenInput.value).then(function () {
                    statusMsg.textContent = 'Token berhasil disalin ke papan klip!';
                    setTimeout(function () { statusMsg.textContent = ''; }, 3000);
                }).catch(function () {
                    tokenInput.select();
                    document.execCommand('copy');
                    statusMsg.textContent = 'Token berhasil disalin!';
                    setTimeout(function () { statusMsg.textContent = ''; }, 3000);
                });
            });
        }
    });
</script>
@endsection