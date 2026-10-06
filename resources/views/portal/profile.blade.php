@extends('layouts.portal')

@section('title', 'Profil Akun')

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="page-header mb-4">
    <div>
        <h1 class="page-title"><i class="bi bi-person-gear text-cyan me-2"></i> Profil & Pengaturan Akun</h1>
        <p class="page-subtitle mb-0">Kelola identitas akun, foto profil, keamanan kata sandi, dan data perusahaan Anda</p>
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
        <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Personal Info & Password -->
    <div class="col-lg-7">
        <!-- Avatar Photo Card -->
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
                        <form method="POST" action="{{ route('portal.profile.avatar') }}" enctype="multipart/form-data">
                            @csrf
                            <label for="avatar" class="form-label text-dim small mb-1">UNGGAH FOTO BARU (JPG, PNG, GIF, WEBP &middot; MAKS 5MB)</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-upload"></i> Unggah
                                </button>
                            </div>
                            <span class="text-dim" style="font-size: 11px;">Foto akan ditampilkan pada top bar dan log diskusi proyek.</span>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Info Form Card -->
        <div class="card">
            <div class="card-header py-3">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-cyan"></i> Informasi Akun & Keamanan
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('portal.profile.update') }}">
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
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <input type="email" class="form-control" id="email"
                                   value="{{ $user->email }}" disabled readonly>
                            <div class="form-text text-dim" style="font-size: 11px;">Email terdaftar sebagai username login.</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                   value="{{ old('phone', $user->phone) }}" placeholder="+62 812-3456-7890" maxlength="20">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label fw-semibold">Jabatan / Posisi</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position"
                                   value="{{ old('position', $user->position) }}" placeholder="Contoh: IT Security Manager / CISO">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="bio" class="form-label fw-semibold">Bio / Catatan Diri</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="2"
                                  placeholder="Catatan singkat perihal tanggung jawab Anda di portal ini...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Password Update Section -->
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

    <!-- Right Column: Company Info & API Token -->
    <div class="col-lg-5">
        <!-- Company Details Card -->
        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-building text-cyan"></i> Informasi Perusahaan Klien
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                        <span class="text-muted small">Nama Entitas</span>
                        <span class="text-light small fw-bold">{{ $client->name }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                        <span class="text-muted small">ID Klien Portal</span>
                        <span class="badge bg-secondary font-monospace">#CLI-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                        <span class="text-muted small">Email Kontak Resmi</span>
                        <span class="text-light small">{{ $client->email ?: '—' }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                        <span class="text-muted small">Telepon Resmi</span>
                        <span class="text-light small">{{ $client->phone ?: '—' }}</span>
                    </div>
                    <div class="list-group-item py-2.5 px-3">
                        <span class="text-muted small d-block mb-1">Alamat Kantor Terdaftar</span>
                        <span class="text-light small" style="line-height: 1.5;">{{ $client->address ?: 'Alamat belum dilengkapi.' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Token Card -->
        <div class="card">
            <div class="card-header py-3">
                <h6 class="mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-cyan"></i> Kredensial API Token
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="alert alert-warning py-2 px-3 mb-3 small d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-5"></i>
                    <div>Token ini digunakan untuk integrasi API eksternal. Jangan bagikan kepada pihak mana pun.</div>
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