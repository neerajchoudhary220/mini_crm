<div class="sidebar position-fixed top-0 left-0">
    <div class="text-center py-4 text-white fw-bold fs-4 border-bottom">
        CRM Admin
    </div>

    <ul class="nav flex-column mt-3">

        <li class="nav-item">
            <a href="{{route('dashboard')}}"
                @class(['nav-link','active'=>request()->is('/')])>
                <i class="fa fa-home me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a @class(['nav-link','active'=>request()->is('contact')]) href="{{ route('contact') }}">
                <i class="fa fa-users me-2"></i> Contacts
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('custom-fields*') ? 'active' : '' }}" href="/custom-fields-page">
                <i class="fa fa-table me-2"></i> Custom Fields
            </a>
        </li>

        <li class="nav-item mt-3">
            <a class="nav-link text-danger" href="{{route('logout')}}">
                <i class="fa fa-right-from-bracket me-2"></i> Logout
            </a>
        </li>

    </ul>
</div>