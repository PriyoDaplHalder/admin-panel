@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('header')
    <h1 class="h2">Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <h3 class="card-title mb-4">
                    <i class="fas fa-wave-square me-2" style="color: #667eea;"></i>
                    Welcome to Admin Panel
                </h3>
                <p class="card-text fs-5 text-muted">
                    Hello! How are you?
                </p>
                <div class="mt-4">
                    <i class="fas fa-smile fs-1" style="color: #667eea;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h3 class="card-title">{{ $totalUsers }}</h3>
                <p class="card-text">Total Users</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <i class="fas fa-user-plus fa-2x mb-3"></i>
                <h3 class="card-title">{{ $recentUsers->count() }}</h3>
                <p class="card-text">Recent Users</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center bg-warning text-white">
            <div class="card-body">
                <i class="fas fa-chart-bar fa-2x mb-3"></i>
                <h3 class="card-title">0</h3>
                <p class="card-text">Reports</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center bg-info text-white">
            <div class="card-body">
                <i class="fas fa-cog fa-2x mb-3"></i>
                <h3 class="card-title">5</h3>
                <p class="card-text">Settings</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Recent Users
                </h5>
            </div>
            <div class="card-body">
                @if($recentUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">View All Users</a>
                    </div>
                @else
                    <p class="text-muted text-center">No users found</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-chart-bar me-2"></i>View Reports
                    </button>
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-cog me-2"></i>System Settings
                    </button>
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-download me-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
