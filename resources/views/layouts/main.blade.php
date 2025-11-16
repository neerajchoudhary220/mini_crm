<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ??"Dasboard" }}</title>

    @include('layouts.includes.css')

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
            background: rgb(15, 159, 188);
        }

        .sidebar .active {
            background: #0dcaf0;
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
    <!-- Toast Msg -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="toastBox"></div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/jquery-min.js') }}"></script>
    <script src="{{asset('assets/js/global.js')}}"></script>
    <script>
        function showToast(message, type = "success") {
            let id = "toast" + Date.now();

            let toast = `
        <div id="${id}" class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

            $("#toastBox").append(toast);

            let toastEl = document.getElementById(id);
            let t = new bootstrap.Toast(toastEl);
            t.show();
        }
    </script>
    @stack('custom-js')
</body>

</html>