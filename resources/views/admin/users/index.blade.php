@extends('layouts.admin')

@section('title', 'User List - Admin Panel')

@section('header')
    <h1 class="h2">User List</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">User List</li>
        </ol>
    </nav>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add User
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>List of Users
                </h5>
            </div>
            <div class="card-body">
                <!-- Action Buttons Row -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="btn-group" role="group" aria-label="Table actions">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="copyBtn">
                                <i class="fas fa-copy me-1"></i>Copy
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="csvBtn">
                                <i class="fas fa-file-csv me-1"></i>CSV
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="excelBtn">
                                <i class="fas fa-file-excel me-1"></i>Excel
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="pdfBtn">
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="printBtn">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="colvisBtn">
                                <i class="fas fa-columns me-1"></i>Column Visibility
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2">
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </span>
                                    <small class="text-muted d-block">{{ $user->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-outline-info" title="View User" onclick="viewUser({{ $user->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" title="Edit User" onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete User" onclick="deleteUser({{ $user->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- User details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#usersTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-outline-secondary btn-sm d-none'
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-outline-secondary btn-sm d-none'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-outline-secondary btn-sm d-none'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-outline-secondary btn-sm d-none'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-outline-secondary btn-sm d-none'
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns',
                className: 'btn btn-outline-secondary btn-sm d-none'
            }
        ],
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search users..."
        }
    });

    // Hide default search box
    $('.dataTables_filter').hide();

    // Custom search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Custom button handlers
    $('#copyBtn').on('click', function() {
        table.button('.buttons-copy').trigger();
    });

    $('#csvBtn').on('click', function() {
        table.button('.buttons-csv').trigger();
    });

    $('#excelBtn').on('click', function() {
        table.button('.buttons-excel').trigger();
    });

    $('#pdfBtn').on('click', function() {
        table.button('.buttons-pdf').trigger();
    });

    $('#printBtn').on('click', function() {
        table.button('.buttons-print').trigger();
    });

    $('#colvisBtn').on('click', function() {
        table.button('.buttons-colvis').trigger();
    });
});

// Action functions
function viewUser(userId) {
    // Show loading content
    $('#modalContent').html(`
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading user details...</p>
        </div>
    `);
    
    // Show modal
    $('#userModal').modal('show');
    
    // Simulate loading user data (replace with actual AJAX call)
    setTimeout(function() {
        $('#modalContent').html(`
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="text-white fw-bold fs-2">U</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <h5>User Information</h5>
                    <table class="table table-borderless">
                        <tr><td><strong>ID:</strong></td><td>${userId}</td></tr>
                        <tr><td><strong>Name:</strong></td><td>Sample User</td></tr>
                        <tr><td><strong>Email:</strong></td><td>user@example.com</td></tr>
                        <tr><td><strong>Registered:</strong></td><td>Jan 15, 2024</td></tr>
                        <tr><td><strong>Status:</strong></td><td><span class="badge bg-success">Active</span></td></tr>
                    </table>
                </div>
            </div>
        `);
    }, 1000);
}

function editUser(userId) {
    alert('Edit user functionality will be implemented. User ID: ' + userId);
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        alert('Delete user functionality will be implemented. User ID: ' + userId);
    }
}
</script>
@endpush
