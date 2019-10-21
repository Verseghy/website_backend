<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

@can('edit users')
<li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
      <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
      <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
    </ul>
</li>
@endcan

@can('edit posts')
<li class="treeview">
    <a href="#"><i class="fa fa-align-justify"></i> <span>Posts, Labels, Authors</span> <i class="fa fa-angle-left pull-right"></i></a>

    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('posts') }}"><i class="fa fa-paragraph"></i> <span>Manage Posts</span></a></li>
        @can('edit labels')
        <li><a href="{{ backpack_url('labels') }}"><i class="fa fa-tag"></i> <span>Manage Labels</span></a></li>
        @endcan
        @can('edit authors')
        <li><a href="{{ backpack_url('authors') }}"><i class="fa fa-id-card"></i> <span>Manage Authors</span></a></li>
        @endcan
    </ul>
</li>
@endcan

@can('edit canteens')
<li class="treeview">
    <a href="#"><i class="fa fa-cutlery "></i> <span>Canteen</span> <i class="fa fa-angle-left pull-right"></i></a>

    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('canteens') }}"><i class="fa fa-list-alt"></i> <span>Canteens</span></a></li>
        <li><a href="{{ backpack_url('menus') }}"><i class="fa fa-apple"></i> <span>Menus</span></a></li>
    </ul>
</li>
@endcan

@can('edit events')
<li><a href="{{ backpack_url('events') }}"><i class="fa fa-calendar"></i> <span>Events</span></a></li>
@endcan

@can('edit colleagues')
<li><a href="{{ backpack_url('colleagues') }}"><i class="fa fa-user"></i> <span>Manage Colleagues</span></a></li>
@endcan

@can('edit newsletter')
<li><a href="{{ backpack_url('newsletter') }}"><i class="fa fa-envelope"></i> <span>Manage Newsletter</span></a></li>
@endcan
