@extends('layouts.app')

@section('title', 'Repositori Berkas')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div>
            <h3 class="page-title"><i class="bi bi-folder2-open text-cyan me-2"></i> Repositori Berkas & Dokumen</h3>
            <p class="page-subtitle mb-0">Kelola dan bagikan dokumen audit, laporan keamanan, dan berkas arsip sistem</p>
        </div>
        <div class="page-header-actions">
            <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#importUrlModal">
                <i class="bi bi-link-45deg"></i> Import dari URL
            </button>
            <a href="{{ route('files.upload') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-cloud-arrow-up"></i> Unggah Berkas
            </a>
        </div>
    </div>

    <!-- Modal Import URL -->
    <div class="modal fade" id="importUrlModal" tabindex="-1" aria-labelledby="importUrlModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.files.import-url') }}">
                    @csrf
                    <div class="modal-header py-3">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="importUrlModalLabel">
                            <i class="bi bi-link-45deg text-cyan"></i> Import Berkas dari URL
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL Berkas Sumber <span class="text-danger">*</span></label>
                            <input type="url" name="file_url" class="form-control" placeholder="https://internal.domain/reports/scan.pdf" required>
                            <div class="form-text text-dim">Mendukung unduhan internal dan eksternal yang diizinkan.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi / Catatan</label>
                            <input type="text" name="description" class="form-control" placeholder="Catatan singkat perihal berkas ini...">
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_public" id="is_public_modal" value="1">
                            <label class="form-check-label fw-semibold" for="is_public_modal">Jadikan berkas ini dapat diakses publik</label>
                        </div>
                    </div>
                    <div class="modal-footer py-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-download"></i> Mulai Import
                        </button>
                    </div>
                </form>
            </div>
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

    <!-- Main Card & Filter -->
    <div class="card">
        <div class="card-header border-bottom py-3">
            <form method="GET" action="{{ route('files.index') }}" class="w-100">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-tertiary border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama berkas atau deskripsi..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <select name="type" class="form-select">
                            <option value="">Semua Tipe Berkas</option>
                            <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>Dokumen PDF (.pdf)</option>
                            <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Gambar (.png, .jpg, .jpeg)</option>
                            <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>Teks & Konfigurasi (.txt, .log, .csv)</option>
                            <option value="word" {{ request('type') == 'word' ? 'selected' : '' }}>Word Document (.doc, .docx)</option>
                            <option value="sheet" {{ request('type') == 'sheet' ? 'selected' : '' }}>Spreadsheet (.xls, .xlsx)</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('type'))
                            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Nama Berkas</th>
                        <th>Tipe / Format</th>
                        <th>Ukuran</th>
                        <th>Pengunggah</th>
                        <th>Waktu Unggah</th>
                        <th>Akses</th>
                        <th class="text-end pe-3" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($files as $file)
                    @php
                        $badge = $file->typeBadge();
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-secondary font-monospace">#FIL-{{ str_pad($file->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="fs-4 text-cyan d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi {{ $badge['icon'] ?? 'bi-file-earmark' }}"></i>
                                </div>
                                <div>
                                    <a href="{{ route('files.show', $file) }}" class="fw-semibold text-decoration-none text-light hover-cyan d-block">
                                        {{ $file->original_name }}
                                    </a>
                                    @if($file->description)
                                        <small class="text-muted d-inline-block text-truncate" style="max-width: 320px;">
                                            {{ $file->description }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary font-monospace text-muted">
                                {{ strtoupper($file->extension()) }}
                            </span>
                        </td>
                        <td>
                            <span class="small font-monospace text-light">{{ $file->humanSize() }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar avatar-xs">
                                    {{ strtoupper(substr($file->uploader->name ?? 'U', 0, 2)) }}
                                </div>
                                <span class="small text-muted">{{ $file->uploader->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $file->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td>
                            @if($file->is_public)
                                <span class="badge bg-success rounded-pill px-2.5 py-1">Publik</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-2.5 py-1">Privat</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('files.show', $file) }}" class="btn btn-outline-secondary" title="Detail Berkas">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('files.download', $file) }}" class="btn btn-outline-secondary" title="Unduh Berkas">
                                    <i class="bi bi-download"></i>
                                </a>
                                @if($file->isTextFile())
                                    <a href="{{ route('files.view', $file) }}" class="btn btn-outline-secondary" title="Baca Isi Berkas">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                @endif
                                @if(auth()->user()->isAdmin() || $file->uploaded_by === auth()->id())
                                    <form action="{{ route('files.destroy', $file) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Berkas" onclick="return confirm('Hapus berkas ini secara permanen?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-folder-x empty-icon text-muted"></i>
                                <div class="empty-title">Belum Ada Berkas</div>
                                <div class="empty-desc">Tidak ditemukan berkas yang cocok dengan filter pencarian Anda.</div>
                                <a href="{{ route('files.upload') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-cloud-arrow-up"></i> Unggah Berkas Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($files instanceof \Illuminate\Pagination\LengthAwarePaginator && $files->hasPages())
        <div class="card-footer border-top d-flex justify-content-between align-items-center py-3">
            <span class="small text-muted">
                Menampilkan {{ $files->firstItem() ?? 0 }} - {{ $files->lastItem() ?? 0 }} dari total {{ $files->total() }} berkas
            </span>
            <div>
                {{ $files->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection