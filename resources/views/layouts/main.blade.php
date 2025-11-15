<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ??"Dasboard" }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #2c3e50;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding-left: 20px;
        }

        .sidebar .nav-link:hover {
            background: #34495e;
        }

        .sidebar .active {
            background: #1abc9c;
        }

        .content-wrapper {
            margin-left: 250px;
        }

        .topnav {
            height: 60px;
        }
    </style>

    @stack('custom-styles')
</head>

<body>

    <!-- Sidebar -->
    @include('layouts.includes.sidebar')

    <!-- Main Content Wrapper -->
    <div class="content-wrapper">

        <!-- Top Navbar -->
        @include('layouts.includes.topnav')

        <!-- Main Content -->
        <div class="container-fluid py-4">
            @yield('contents')
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('custom-scripts')
</body>

</html>