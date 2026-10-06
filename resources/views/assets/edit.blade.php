@extends('layouts.app')

@section('title', 'Ubah Aset - ' . $asset->hostname)

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('assets.index') }}" class="text-muted"><i class="bi bi-hdd-network"></i> Aset</a>
                <span class="sep">/</span>
                <a href="{{ route('assets.show', $asset) }}" class="text-muted">{{ $asset->hostname }}</a>
                <span class="sep">/</span>
                <span class="current">Ubah</span>
            </div>
            <h3 class="page-title mb-0">Ubah Data Aset: <span class="font-monospace text-cyan">{{ $asset->hostname }}</span></h3>
        </div>
        <div>
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Terdapat kesalahan pengisian formulir:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card border border-subtle">
        <div class="card-header py-3 border-bottom">
            <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                <i class="bi bi-pencil-square text-cyan"></i> Perbarui Data Aset
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('assets.update', $asset) }}">
                @csrf
                @method('PUT')

                <!-- Section 1: Identitas & Jaringan -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-uppercase text-cyan fw-semibold small mb-3 border-bottom pb-2">
                            <i class="bi bi-hdd me-1"></i> 1. Informasi Jaringan & Perangkat
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dim small fw-semibold">HOSTNAME <span class="text-danger">*</span></label>
                        <input type="text" name="hostname" class="form-control font-monospace @error('hostname') is-invalid @enderror"
                               value="{{ old('hostname', $asset->hostname) }}" required>
                        @error('hostname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dim small fw-semibold">IP ADDRESS <span class="text-danger">*</span></label>
                        <input type="text" name="ip_address" class="form-control font-monospace @error('ip_address') is-invalid @enderror"
                               value="{{ old('ip_address', $asset->ip_address) }}" required>
                        @error('ip_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dim small fw-semibold">TIPE ASET <span class="text-danger">*</span></label>
                        <select name="asset_type" class="form-select @error('asset_type') is-invalid @enderror" required>
                            @foreach([
                                'server' => 'Server',
                                'workstation' => 'Workstation',
                                'firewall' => 'Firewall',
                                'switch' => 'Network Switch',
                                'router' => 'Router',
                                'access-point' => 'Access Point',
                                'storage' => 'Storage NAS/SAN',
                                'hypervisor' => 'Hypervisor'
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('asset_type', $asset->asset_type) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('asset_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dim small fw-semibold">VERSI SISTEM OPERASI (OS)</label>
                        <input type="text" name="os_version" class="form-control @error('os_version') is-invalid @enderror"
                               value="{{ old('os_version', $asset->os_version) }}">
                        @error('os_version') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Section 2: Departemen & Scan -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-uppercase text-cyan fw-semibold small mb-3 border-bottom pb-2">
                            <i class="bi bi-building me-1"></i> 2. Departemen & Riwayat Scan
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dim small fw-semibold">DEPARTEMEN PEMILIK <span class="text-danger">*</span></label>
                        <select name="owner_department" class="form-select @error('owner_department') is-invalid @enderror" required>
                            @foreach(['SOC','NOC','IT Infrastructure','Compliance','Finance','HR'] as $d)
                                <option value="{{ $d }}" @selected(old('owner_department', $asset->owner_department) === $d)>{{ $d }}</option>
                            @endforeach
                        </select>
                        @error('owner_department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dim small fw-semibold">TANGGAL SCAN TERAKHIR</label>
                        <input type="date" name="last_scan_date" class="form-control @error('last_scan_date') is-invalid @enderror"
                               value="{{ old('last_scan_date', $asset->last_scan_date?->format('Y-m-d')) }}">
                        @error('last_scan_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Section 3: Catatan -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-uppercase text-cyan fw-semibold small mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-1"></i> 3. Catatan Inventaris
                        </h6>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-dim small fw-semibold">CATATAN TAMBAHAN</label>
                        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $asset->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
