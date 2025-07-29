@extends ('layouts.sidebar')

@section('title', 'Users - Admin Panel')

@section('content')
    <h1 class="h2">User List</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">User List</li>
        </ol>
    </nav>
@endsection