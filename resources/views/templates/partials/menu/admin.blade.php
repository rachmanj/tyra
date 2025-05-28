<li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        class="nav-link dropdown-toggle">Admin</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">

        <li><a href="{{ route('users.index') }}" class="dropdown-item">User List</a></li>

        @can('access_roles')
            <li><a href="{{ route('roles.index') }}" class="dropdown-item">Roles</a></li>
        @endcan
        @can('access_permissions')
            <li><a href="{{ route('permissions.index') }}" class="dropdown-item">Permissions</a></li>
        @endcan
        <li><a href="{{ route('announcements.index') }}" class="dropdown-item">Announcements</a></li>
    </ul>
</li>
