<nav class="navbar navbar-light bg-white shadow-sm px-4 topnav d-flex justify-content-end gap-2 align-items-center">

    <h5 class="mb-0">Welcome, {{auth()->user()->name}}</h5>
    <a class=" text-white btn btn-danger" href="{{route('logout')}}">
        <i class="fa fa-right-from-bracket me-2"></i> Logout
    </a>
</nav>