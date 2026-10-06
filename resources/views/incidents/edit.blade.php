@extends('layouts.app')

@section('title', 'Ubah Insiden - ' . $incident->ticket_number)

@section('content')
@php
    $usersData = $users->map(fn($u) => [
        'id' => (int) $u->id,
        'name' => $u->name,
        'email' => $u->email,
        'role' => $u->role ?? 'Analyst',
    ])->values()->all();
    $assignedAssignees = array_map('intval', (array) old('assigned_to', $incident->assignees->pluck('id')->toArray()));
@endphp

<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="breadcrumb-nav mb-2">
                <a href="{{ route('incidents.index') }}" class="text-muted"><i class="bi bi-clipboard2-pulse"></i> Insiden</a>
                <span class="sep">/</span>
                <a href="{{ route('incidents.show', $incident) }}" class="text-muted">{{ $incident->ticket_number }}</a>
                <span class="sep">/</span>
                <span class="current">Ubah</span>
            </div>
            <h3 class="page-title mb-0">Ubah Tiket Insiden {{ $incident->ticket_number }}</h3>
        </div>
        <div>
            <a href="{{ route('incidents.show', $incident) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Terdapat kesalahan pengisian:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-cyan"></i> Formulir Perubahan Insiden
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('incidents.update', $incident) }}" id="form-incident">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <!-- Judul Insiden -->
                    <div class="col-12">
                        <label class="form-label text-dim">JUDUL INSIDEN <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $incident->title) }}" placeholder="Judul insiden" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12">
                        <label class="form-label text-dim">DESKRIPSI & KRONOLOGI</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Detail analisis atau kronologi insiden...">{{ old('description', $incident->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Prioritas & Tingkat Keparahan -->
                    <div class="col-md-4">
                        <label class="form-label text-dim">PRIORITAS <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                            @foreach(['low' => 'Low (Rendah)', 'medium' => 'Medium (Sedang)', 'high' => 'High (Tinggi)', 'critical' => 'Critical (Kritis)'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('priority', $incident->priority) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label text-dim">STATUS <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['open' => 'Open (Terbuka)', 'in_progress' => 'In Progress (Dikerjakan)', 'resolved' => 'Resolved (Selesai)'] as $s => $label)
                                <option value="{{ $s }}" @selected(old('status', $incident->status) === $s)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Aset Terdampak -->
                    <div class="col-md-4">
                        <label class="form-label text-dim">ASET TERDAMPAK <span class="text-danger">*</span></label>
                        <select name="asset_id" class="form-select @error('asset_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Aset --</option>
                            @foreach($assets as $a)
                                <option value="{{ $a->id }}" @selected((string)old('asset_id', $incident->asset_id) === (string)$a->id)>
                                    {{ $a->hostname }} {{ $a->ip_address ? "({$a->ip_address})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Multiple Assignees (Penugasan Analis Mandatory) -->
                    <div class="col-12">
                        <label class="form-label text-dim d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-people me-1"></i> DITUGASKAN KEPADA (TIM ANALIS / PIC) <span class="text-danger">*</span></span>
                            <span class="text-muted small" id="assignee-count-label">0 analis dipilih</span>
                        </label>

                        <div class="p-3 bg-tertiary rounded border @error('assigned_to') border-danger @else border-subtle @enderror">
                            <!-- Container Daftar Analis Terpilih -->
                            <div id="assignees-list" class="d-flex flex-column gap-2 mb-3">
                                <!-- Dynamic JS render -->
                            </div>

                            <hr class="my-3">

                            <!-- Form Dropdown Penambahan Analis -->
                            <div class="row g-2 align-items-center">
                                <div class="col-sm-8 col-md-9">
                                    <select id="analyst-select" class="form-select form-select-sm">
                                        <option value="" disabled selected>Pilih analis untuk ditambahkan...</option>
                                        <!-- Dynamic JS options -->
                                    </select>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <button type="button" id="btn-add-analyst" class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-person-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('assigned_to')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top border-subtle">
                    <a href="{{ route('incidents.show', $incident) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allUsers = {!! json_encode($usersData) !!};
    let selectedIds = {!! json_encode($assignedAssignees) !!};

    const formEl = document.getElementById('form-incident');
    const listContainer = document.getElementById('assignees-list');
    const selectEl = document.getElementById('analyst-select');
    const btnAdd = document.getElementById('btn-add-analyst');
    const countLabel = document.getElementById('assignee-count-label');

    function render() {
        if (selectedIds.length === 0) {
            listContainer.innerHTML = `
                <div class="text-muted small p-3 bg-secondary-soft rounded text-center border border-dashed border-subtle">
                    <i class="bi bi-person-exclamation fs-4 d-block text-warning mb-1"></i>
                    <span class="text-warning fw-semibold">Belum ada Analis / PIC yang ditugaskan.</span><br>
                    Pilih minimal 1 analis dari dropdown di bawah lalu klik tombol "Tambah".
                </div>
            `;
        } else {
            let html = '';
            selectedIds.forEach(id => {
                const user = allUsers.find(u => u.id === id);
                if (!user) return;
                const initials = (user.name || 'U').substring(0, 2).toUpperCase();
                const role = user.role ? (user.role.charAt(0).toUpperCase() + user.role.slice(1)) : 'Analyst';

                html += `
                    <div class="p-2.5 bg-secondary-soft rounded d-flex align-items-center justify-content-between border border-subtle">
                        <input type="hidden" name="assigned_to[]" value="${user.id}">
                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                            <div class="avatar avatar-sm flex-shrink-0" style="width: 32px; height: 32px; font-size: 11px;">
                                ${initials}
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-light small text-truncate">${user.name}</div>
                                <div class="text-dim" style="font-size: 11px;">${role} &middot; ${user.email}</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 flex-shrink-0 ms-2" onclick="removeAssignee(${user.id})" title="Hapus Analis">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                `;
            });
            listContainer.innerHTML = html;
        }

        countLabel.textContent = `${selectedIds.length} analis dipilih`;

        const unselectedUsers = allUsers.filter(u => !selectedIds.includes(u.id));
        selectEl.innerHTML = '<option value="" disabled selected>Pilih analis untuk ditambahkan...</option>';

        if (unselectedUsers.length === 0) {
            const opt = document.createElement('option');
            opt.disabled = true;
            opt.textContent = 'Semua analis telah ditugaskan';
            selectEl.appendChild(opt);
            btnAdd.disabled = true;
        } else {
            btnAdd.disabled = false;
            unselectedUsers.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u.id;
                const role = u.role ? (u.role.charAt(0).toUpperCase() + u.role.slice(1)) : 'Analyst';
                opt.textContent = `${u.name} (${role})`;
                selectEl.appendChild(opt);
            });
        }
    }

    btnAdd.addEventListener('click', function() {
        const val = parseInt(selectEl.value);
        if (val && !selectedIds.includes(val)) {
            selectedIds.push(val);
            render();
        }
    });

    window.removeAssignee = function(id) {
        selectedIds = selectedIds.filter(i => i !== id);
        render();
    };

    formEl.addEventListener('submit', function(e) {
        if (selectedIds.length === 0) {
            e.preventDefault();
            alert('Wajib menetapkan minimal satu Analis / PIC untuk menangani insiden ini.');
            selectEl.focus();
        }
    });

    render();
});
</script>
@endsection
