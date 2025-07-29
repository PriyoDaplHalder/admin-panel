<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- bootstrap stylesheet CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Datatables css -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <a href="{{ route('dashboard') }}" class="brand-logo">
                        Admin Panel
                    </a>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'category-active' : '' }}" data-bs-toggle="collapse" href="#usersMenu" role="button"
                                aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}" aria-controls="usersMenu">
                                <i class="fas fa-users me-2"></i>Users
                                <i class="fas fa-chevron-down float-end mt-1 transition-arrow" id="usersMenuArrow"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}"
                                id="usersMenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('users.index') ? 'active subcategory-active' : '' }}"
                                            href="{{ route('users.index') }}">
                                            <i class="fas fa-list me-2"></i>User List
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        @yield('content')
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var usersMenu = document.getElementById('usersMenu');
            var arrow = document.getElementById('usersMenuArrow');
            if (usersMenu && arrow) {
                var updateArrow = function () {
                    if (usersMenu.classList.contains('show')) {
                        arrow.style.transform = 'rotate(0deg)'; // Down
                    } else {
                        arrow.style.transform = 'rotate(-90deg)'; // Side
                    }
                };
                usersMenu.addEventListener('shown.bs.collapse', updateArrow);
                usersMenu.addEventListener('hidden.bs.collapse', updateArrow);
                updateArrow();
            }
        });
    </script>

</body>

</html>