<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
      <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
      <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
    </ul>
</li>
<li><a href="{{ backpack_url('labels') }}"><i class="fa fa-tag"></i> <span>Manage Labels</span></a></li>
<li><a href="{{ backpack_url('authors') }}"><i class="fa fa-tag"></i> <span>Manage Authors</span></a></li>
<li><a href="{{ backpack_url('posts') }}"><i class="fa fa-tag"></i> <span>Manage Posts</span></a></li>
<li><a href="{{ backpack_url('menus') }}"><i class="fa fa-tag"></i> <span>Menus</span></a></li>
<li><a href="{{ backpack_url('canteens') }}"><i class="fa fa-tag"></i> <span>Canteens</span></a></li>
<li><a href="{{ backpack_url('events') }}"><i class="fa fa-tag"></i> <span>Events</span></a></li>
<li><a href="{{ backpack_url('newsletter') }}"><i class="fa fa-tag"></i> <span>Manage Newsletter</span></a></li>
