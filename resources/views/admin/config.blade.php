@extends('layouts.app')
@section('title', 'Konfigurasi Sistem')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4><i class="bi bi-sliders"></i> Ringkasan Konfigurasi Sistem</h4>
    <a href="{{ route('admin.config', ['refresh' => 1]) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-clockwise"></i> Muat Ulang
    </a>
</div>
<p class="text-muted">Berakhir pada {{ $refreshedAt }}. Nilai sensitif ditampilkan dalam bentuk tersamar.</p>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-header">Umum</div>
            <div class="table-responsive"><table class="table table-sm mb-0">
                @foreach($general as $k => $v)<tr><th style="width:45%">{{ $k }}</th><td>{{ $v }}</td></tr>@endforeach
            </table></div></div>
        <div class="card"><div class="card-header">Infrastruktur</div>
            <div class="table-responsive"><table class="table table-sm mb-0">
                @foreach($infrastructure as $k => $v)<tr><th style="width:45%">{{ $k }}</th><td>{{ $v }}</td></tr>@endforeach
            </table></div></div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-header">Integrasi</div>
            <div class="table-responsive"><table class="table table-sm mb-0">
                @foreach($integration as $k => $v)<tr><th style="width:45%">{{ $k }}</th><td>{{ $v }}</td></tr>@endforeach
            </table></div></div>
        <div class="card"><div class="card-header">Pemeliharaan</div>
            <div class="table-responsive"><table class="table table-sm mb-0">
                @foreach($maintenance as $k => $v)<tr><th style="width:45%">{{ $k }}</th><td class="text-break">{{ $v }}</td></tr>@endforeach
            </table></div></div>
    </div>
</div>
@endsection
