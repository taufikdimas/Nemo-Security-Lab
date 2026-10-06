@extends('layouts.app')

@section('title', 'Ubah Data Klien - ' . $client->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('clients.index') }}" class="text-muted"><i class="bi bi-people"></i> Klien</a>
                <span class="sep">/</span>
                <a href="{{ route('clients.show', $client->id) }}" class="text-muted">#CLI-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}</a>
                <span class="sep">/</span>
                <span class="current">Ubah Data</span>
            </div>

            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-cyan"></i> Ubah Data Klien #CLI-{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}
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

                    <form method="POST" action="{{ route('clients.update', $client->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap / Kontak <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $client->name) }}" 
                                       placeholder="Contoh: Alexander Pratama" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="company" class="form-label fw-semibold">Nama Perusahaan / Organisasi</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                       id="company" name="company" value="{{ old('company', $client->company) }}" 
                                       placeholder="Contoh: PT Cyber Solusi Nusantara">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $client->email) }}" 
                                       placeholder="alex@cybersolusi.id">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $client->phone) }}" 
                                       placeholder="+62 812-3456-7890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Alamat Lengkap Kantor</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Gedung Cyber Tower Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan...">{{ old('address', $client->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-secondary">
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