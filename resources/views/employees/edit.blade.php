@extends('layouts.app')

@section('title', 'Ubah Data Team - ' . $employee->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav mb-3">
                <a href="{{ route('employees.index') }}" class="text-muted"><i class="bi bi-people-fill"></i> Team</a>
                <span class="sep">/</span>
                <a href="{{ route('employees.show', $employee->id) }}" class="text-muted">#TM-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</a>
                <span class="sep">/</span>
                <span class="current">Ubah Data</span>
            </div>

            <div class="card border border-subtle">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0 text-light fw-semibold d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-pencil-square text-cyan"></i> Ubah Data Anggota Team #TM-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}
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

                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="avatar rounded-circle border border-2 border-cyan shadow-sm" style="width: 56px; height: 56px; object-fit: cover;">
                        <div>
                            <h5 class="mb-0 text-light">{{ $employee->name }}</h5>
                            <span class="text-dim small">{{ $employee->position ?: 'Anggota Team' }} &middot; {{ $employee->department }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 p-3 bg-tertiary rounded border border-subtle">
                            <label for="photo" class="form-label fw-semibold text-cyan d-flex align-items-center gap-1.5 mb-1" style="font-size: 13px;">
                                <i class="bi bi-camera"></i> Ganti Foto Profil (JPG, PNG, GIF, WEBP &middot; Maks 5MB)
                            </label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                            <div class="form-text text-dim" style="font-size: 11.5px;">Unggah file gambar baru untuk mengganti foto profil anggota team ini.</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-dim small fw-semibold">NAMA LENGKAP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $employee->name) }}" 
                                       placeholder="Contoh: Dimas Wahyu Pratama" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-dim small fw-semibold">EMAIL KERJA / AKUN <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $employee->email) }}" 
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
                                    @php $currentDept = old('department', $employee->department); @endphp
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" @selected($currentDept === $dept)>{{ $dept }}</option>
                                    @endforeach
                                    @if($currentDept && !in_array($currentDept, $departments))
                                        <option value="{{ $currentDept }}" selected>{{ $currentDept }}</option>
                                    @endif
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label text-dim small fw-semibold">JABATAN / PERAN</label>
                                <select class="form-select @error('position') is-invalid @enderror" id="position" name="position">
                                    <option value="">-- Pilih Peran / Jabatan --</option>
                                    @php $currentPos = old('position', $employee->position); @endphp
                                    @php $foundPos = false; @endphp
                                    @foreach($positions as $group => $roles)
                                        <optgroup label="{{ $group }}">
                                            @foreach($roles as $role)
                                                @if($currentPos === $role) @php $foundPos = true; @endphp @endif
                                                <option value="{{ $role }}" data-dept="{{ $group }}" @selected($currentPos === $role)>{{ $role }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                    @if($currentPos && !$foundPos)
                                        <option value="{{ $currentPos }}" selected>{{ $currentPos }}</option>
                                    @endif
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label text-dim small fw-semibold">NOMOR TELEPON / WHATSAPP</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" 
                                   placeholder="+62 812-3456-7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-secondary">
                                Batal
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