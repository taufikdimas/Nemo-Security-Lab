@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2>{{ $totalUsers }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-white">Manage Users →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2>{{ $totalProducts }}</h2>
                    <a href="{{ route('products.index') }}" class="text-white">View Products →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Files</h5>
                    <h2>{{ $totalFiles }}</h2>
                    <a href="{{ route('admin.files.index') }}" class="text-white">Manage Files →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-0">
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Clients</h5>
                    <h2>{{ $totalClients }}</h2>
                    <span class="text-white">Perusahaan terdaftar</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Client Portal Accounts</h5>
                    <h2>{{ $clientUsers }}</h2>
                    <a href="{{ route('admin.users.index', ['role' => 'client']) }}" class="text-white">Manage Client Users →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Recent Users</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">{{ $user->role }}</span></td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection