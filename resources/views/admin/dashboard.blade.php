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

@endsection
