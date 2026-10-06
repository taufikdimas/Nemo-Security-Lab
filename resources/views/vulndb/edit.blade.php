@extends('layouts.app')

@section('title', 'Ubah Kerentanan - ' . ($vuln->cve_id ?: $vuln->name))

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('vulndb.index') }}" class="text-muted"><i class="bi bi-shield-exclamation"></i> Basis Kerentanan</a>
                <span class="sep">/</span>
                <a href="{{ route('vulndb.show', $vuln->id) }}" class="text-muted">{{ $vuln->cve_id ?: ('#VULN-' . str_pad($vuln->id, 3, '0', STR_PAD_LEFT)) }}</a>
                <span class="sep">/</span>
                <span class="current">Ubah Data</span>
            </div>

            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-cyan"></i> Ubah Data Kerentanan
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

                    <form method="POST" action="{{ route('vulndb.update', $vuln->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label for="cve_id" class="form-label fw-semibold">CVE ID / Identifier</label>
                                <input type="text" class="form-control @error('cve_id') is-invalid @enderror" 
                                       id="cve_id" name="cve_id" value="{{ old('cve_id', $vuln->cve_id) }}" 
                                       placeholder="Contoh: CVE-2024-38819">
                                @error('cve_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-7">
                                <label for="name" class="form-label fw-semibold">Nama Kerentanan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $vuln->name) }}" 
                                       placeholder="Contoh: Remote Code Execution via Insecure Deserialization" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="severity" class="form-label fw-semibold">Tingkat Keparahan <span class="text-danger">*</span></label>
                                <select class="form-select @error('severity') is-invalid @enderror" id="severity" name="severity" required>
                                    <option value="low" {{ old('severity', $vuln->severity) == 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                                    <option value="medium" {{ old('severity', $vuln->severity) == 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                                    <option value="high" {{ old('severity', $vuln->severity) == 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                                    <option value="critical" {{ old('severity', $vuln->severity) == 'critical' ? 'selected' : '' }}>Kritis (Critical)</option>
                                </select>
                                @error('severity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="cvss_score" class="form-label fw-semibold">Skor CVSS (0.0 - 10.0)</label>
                                <input type="number" step="0.1" min="0" max="10" class="form-control @error('cvss_score') is-invalid @enderror" 
                                       id="cvss_score" name="cvss_score" value="{{ old('cvss_score', $vuln->cvss_score) }}" 
                                       placeholder="Contoh: 9.8">
                                @error('cvss_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="published_year" class="form-label fw-semibold">Tahun Publikasi</label>
                                <input type="text" maxlength="4" class="form-control @error('published_year') is-invalid @enderror" 
                                       id="published_year" name="published_year" value="{{ old('published_year', $vuln->published_year) }}" 
                                       placeholder="2024">
                                @error('published_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold">Kategori / Vektor Serangan</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                       id="category" name="category" value="{{ old('category', $vuln->category) }}" 
                                       placeholder="Contoh: Injection / Authentication Bypass">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="affected_systems" class="form-label fw-semibold">Sistem Terdampak</label>
                                <input type="text" class="form-control @error('affected_systems') is-invalid @enderror" 
                                       id="affected_systems" name="affected_systems" value="{{ old('affected_systems', $vuln->affected_systems) }}" 
                                       placeholder="Contoh: Apache Tomcat < 10.1, Spring Framework">
                                @error('affected_systems')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi & Analisis Teknis</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Jelaskan mekanisme kerentanan, bagaimana serangan dieksekusi, dan dampak terhadap sistem...">{{ old('description', $vuln->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="remediation" class="form-label fw-semibold">Panduan Remediasi & Mitigasi</label>
                            <textarea class="form-control @error('remediation') is-invalid @enderror" 
                                      id="remediation" name="remediation" rows="3" 
                                      placeholder="Langkah patch, konfigurasi firewall/WAF, atau workaround sementara...">{{ old('remediation', $vuln->remediation) }}</textarea>
                            @error('remediation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('vulndb.show', $vuln->id) }}" class="btn btn-outline-secondary">
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