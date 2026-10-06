@extends('layouts.app')

@section('title', 'Unggah Berkas Baru')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('files.index') }}" class="text-muted"><i class="bi bi-folder2-open"></i> Berkas</a>
                <span class="sep">/</span>
                <span class="current">Unggah Berkas Baru</span>
            </div>

            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-cloud-arrow-up text-cyan"></i> Unggah Berkas ke Sistem
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

                    <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold">Pilih File Dokumen <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required autofocus>
                            <div class="form-text text-dim">
                                Mendukung format: <code>PDF, DOC, DOCX, XLS, XLSX, TXT, CSV, PNG, JPG, JPEG</code> (Maks. 20MB)
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi / Catatan Tambahan</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Tambahkan catatan mengenai tujuan atau isi berkas ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="p-3 bg-tertiary rounded border border-subtle">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-light" for="is_public">
                                        Jadikan Berkas Ini Bersifat Publik
                                    </label>
                                    <div class="text-dim small mt-1">
                                        Berkas publik dapat diunduh dan dilihat oleh pengguna portal dan klien terkait.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-cloud-arrow-up"></i> Unggah Berkas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection