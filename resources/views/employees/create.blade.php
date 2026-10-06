@extends('layouts.app')

@section('title', 'Tambah Anggota Team')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('employees.index') }}" class="text-muted"><i class="bi bi-people-fill"></i> Team</a>
                <span class="sep">/</span>
                <span class="current">Tambah Anggota</span>
            </div>

            <div class="card border border-subtle">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-person-plus text-cyan"></i> Formulir Anggota Team Baru
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

                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-dim small fw-semibold">NAMA LENGKAP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Contoh: Dimas Wahyu Pratama" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-dim small fw-semibold">EMAIL KERJA / AKUN <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="dimas@company.id" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="department" class="form-label text-dim small fw-semibold">DIVISI / DEPARTEMEN</label>
                                <select class="form-select @error('department') is-invalid @enderror" id="department" name="department">
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" @selected(old('department') === $dept)>{{ $dept }}</option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label text-dim small fw-semibold">JABATAN / PERAN</label>
                                <select class="form-select @error('position') is-invalid @enderror" id="position" name="position">
                                    <option value="">-- Pilih Peran / Jabatan --</option>
                                    @foreach($positions as $group => $roles)
                                        <optgroup label="{{ $group }}">
                                            @foreach($roles as $role)
                                                <option value="{{ $role }}" data-dept="{{ $group }}" @selected(old('position') === $role)>{{ $role }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label text-dim small fw-semibold">NOMOR TELEPON / WHATSAPP</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+62 812-3456-7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-check2-circle"></i> Simpan Anggota Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deptSelect = document.getElementById('department');
    const posSelect = document.getElementById('position');

    posSelect.addEventListener('change', function() {
        const selectedOpt = posSelect.options[posSelect.selectedIndex];
        const dept = selectedOpt.getAttribute('data-dept');
        if (dept && !deptSelect.value) {
            deptSelect.value = dept;
        }
    });

    deptSelect.addEventListener('change', function() {
        const selectedDept = deptSelect.value;
        if (!selectedDept) return;

        const currentOpt = posSelect.options[posSelect.selectedIndex];
        const currentDept = currentOpt ? currentOpt.getAttribute('data-dept') : null;
        if (currentDept && currentDept !== selectedDept) {
            posSelect.value = '';
        }
    });
});
</script>
@endsection