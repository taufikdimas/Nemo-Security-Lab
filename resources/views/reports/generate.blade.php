@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-file-earmark-plus"></i> Buat Laporan Insiden</h3>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Form Laporan</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reports.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="report_id" class="form-label">Insiden</label>
                            <select name="report_id" id="report_id"
                                    class="form-select @error('report_id') is-invalid @enderror" required>
                                <option value="">-- Pilih nomor tiket --</option>
                                @foreach($incidents as $incident)
                                    <option value="{{ $incident->id }}"
                                        @selected(old('report_id') == $incident->id)>
                                        {{ $incident->ticket_number }} - {{ $incident->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('report_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label">Format</label>
                            <input type="text" name="format" id="format" class="form-control"
                                   value="{{ old('format', 'txt') }}" maxlength="10" required>
                            <div class="form-text">Ekstensi berkas laporan, misalnya <code>txt</code>.</div>
                            @error('format')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-plus"></i> Buat Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Kriteria Laporan</div>
                <div class="card-body">
                    <p class="mb-2">Laporan memuat ringkasan insiden berikut:</p>
                    <ul class="mb-3">
                        <li>Nomor tiket dan judul insiden</li>
                        <li>Prioritas, status, dan deskripsi</li>
                        <li>Aset terkait, pelapor, dan penerima tugas</li>
                        <li>Waktu pembuatan dan penyelesaian</li>
                    </ul>
                    <p class="mb-0 text-muted">
                        Setelah laporan dibuat, berkas tersedia di daftar
                        <a href="{{ route('reports.index') }}">Laporan</a> untuk diunduh.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
