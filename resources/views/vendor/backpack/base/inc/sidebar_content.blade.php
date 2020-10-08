<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="nav-icon fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

@if(backpack_user()->can('edit users'))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle"><i class="nav-icon fa fa-group"></i> <span>Users</span></a>
    <ul class="nav-dropdown-items">
      <li class='nav-item'><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i> <span>Users</span></a></li>
      <li class='nav-item'><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i> <span>Roles</span></a></li>
      <li class='nav-item'><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li>
    </ul>
</li>
@endif

@if(backpack_user()->can('edit posts'))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-align-justify"></i> <span>Posts</span></a>

    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class="nav-link" href="{{ backpack_url('posts') }}"><i class="nav-icon fa fa-paragraph"></i> <span>Manage Posts</span></a></li>
        @if(backpack_user()->can('edit labels'))
        <li class='nav-item'><a class="nav-link" href="{{ backpack_url('labels') }}"><i class="nav-icon fa fa-tag"></i> <span>Manage Labels</span></a></li>
        @endif
        @if(backpack_user()->can('edit authors'))
        <li class='nav-item'><a class="nav-link" href="{{ backpack_url('authors') }}"><i class="nav-icon fa fa-id-card"></i> <span>Manage Authors</span></a></li>
        @endif
    </ul>
</li>
@endif

@if(backpack_user()->can('edit canteens'))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-cutlery "></i> <span>Canteen</span></a>

    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class="nav-link" href="{{ backpack_url('canteens') }}"><i class="nav-icon fa fa-list-alt"></i> <span>Canteens</span></a></li>
        <li class='nav-item'><a class="nav-link" href="{{ backpack_url('menus') }}"><i class="nav-icon fa fa-apple"></i> <span>Menus</span></a></li>
    </ul>
</li>
@endif

@if(backpack_user()->can('edit events'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('events') }}"><i class="nav-icon fa fa-calendar"></i> <span>Events</span></a></li>
@endif

@if(backpack_user()->can('edit colleagues'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('colleagues') }}"><i class="nav-icon fa fa-user"></i> <span>Colleagues</span></a></li>
@endif

@if(backpack_user()->hasRole('admin'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}\"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-terminal'></i> Logs</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon fa fa-file-o'></i> <span>Pages</span></a></li>
@endif

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('competition') }}'><i class='nav-icon la la-question'></i> Competitions</a></li>