@extends('layouts.app')

@section('title', 'Import Data')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="mb-1"><i class="bi bi-upload"></i> Import Data</h3>
        <p class="text-muted mb-0">Import data from CSV files</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Import Users from CSV</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('import.users') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">CSV File</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" 
                                   accept=".csv,.txt" required>
                            <small class="text-muted">
                                Format: name,email,password<br>
                                Example: John Doe,john@example.com,password123
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Import Users
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-link"></i> Import File from URL</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.files.import-url') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="file_url" class="form-label">File URL</label>
                            <input type="url" class="form-control" id="file_url" name="file_url" 
                                   placeholder="https://example.com/file.txt" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-download"></i> Import File
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection