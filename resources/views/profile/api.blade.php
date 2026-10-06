@extends('layouts.app')

@section('title', 'Kredensial API')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('profile.edit') }}" class="text-muted"><i class="bi bi-person"></i> Profil</a>
                <span class="sep">/</span>
                <span class="current">Akses API</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <h3 class="page-title mb-0"><i class="bi bi-key text-cyan me-2"></i> Akses REST API & Token</h3>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Kembali ke Profil
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main API Details (Left Column) -->
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-cyan"></i> Token Otentikasi Anda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-dim small">API TOKEN AKTIF</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" id="apiToken" value="{{ $user->api_token }}" readonly>
                            <button class="btn btn-outline-primary d-inline-flex align-items-center gap-1" type="button" id="copyToken">
                                <i class="bi bi-clipboard"></i> Salin Token
                            </button>
                        </div>
                        <div class="form-text text-success mt-2" id="copyStatus" role="status" aria-live="polite"></div>
                    </div>

                    <div class="row g-2 mt-3 pt-3 border-top">
                        <div class="col-sm-6">
                            <div class="p-2.5 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1">PEMILIK KREDENSIAL</span>
                                <span class="fw-semibold text-light small">{{ $user->name }} ({{ $user->email }})</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2.5 bg-tertiary rounded">
                                <span class="text-dim small d-block mb-1">TANGGAL DITERBITKAN</span>
                                <span class="fw-semibold text-light small">{{ $user->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Example Usage Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-terminal text-cyan"></i> Contoh Pemanggilan Endpoint (cURL / HTTP)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 bg-tertiary rounded-bottom">
                        <div class="text-dim small mb-2 font-monospace">1. Mengambil Feed Inventaris Aset:</div>
                        <pre class="mb-3 font-monospace text-cyan" style="font-size: 13px; line-height: 1.5; background: #0c101b; padding: 12px; border-radius: 6px; border: 1px solid var(--border-color);">curl -X GET "{{ url('/api/v1/assets') }}" \
  -H "X-API-Token: {{ $user->api_token }}" \
  -H "Accept: application/json"</pre>

                        <div class="text-dim small mb-2 font-monospace">2. Mengambil Feed Tiket Insiden:</div>
                        <pre class="mb-0 font-monospace text-cyan" style="font-size: 13px; line-height: 1.5; background: #0c101b; padding: 12px; border-radius: 6px; border: 1px solid var(--border-color);">curl -X GET "{{ url('/api/v1/incidents') }}" \
  -H "X-API-Token: {{ $user->api_token }}" \
  -H "Accept: application/json"</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Warning & Security Notice (Right Column) -->
        <div class="col-lg-5">
            <div class="card border-warning mb-4">
                <div class="card-header py-3 d-flex align-items-center gap-2 text-warning">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <h6 class="mb-0 fw-bold">Panduan Keamanan Token</h6>
                </div>
                <div class="card-body">
                    <p class="small text-light mb-2">
                        Token API ini memberikan hak akses setara dengan login akun Anda untuk mengambil feed data keamanan internal.
                    </p>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Jangan pernah membagikan atau mem-commit token ini ke repositori publik (GitHub/GitLab).</li>
                        <li class="mb-1">Gunakan header HTTP <code>X-API-Token</code> pada setiap request.</li>
                        <li>Jika terjadi indikasi kebocoran, segera hubungi tim administrator untuk menerbitkan ulang (*revoke & regenerate*).</li>
                    </ul>
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
