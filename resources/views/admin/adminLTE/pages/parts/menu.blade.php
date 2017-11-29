    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-files-o"></i>
          <span>Lists of </span>
          <span class="pull-right-container">
            <span class="label label-primary pull-right">3</span>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/admin/users"><i class="fa fa-circle-o"></i> <span>Users</span><span class="pull-right-container"><small class="label pull-right bg-red">{{ $countRecords['users'] }}</small></span></a></li>
          <li><a href="/admin/posts"><i class="fa fa-circle-o"></i><span>Posts</span><span class="pull-right-container"><small class="label pull-right bg-red">{{ $countRecords['posts'] }}</small></span></a></li>
          <li><a href="/admin/reports"><i class="fa fa-circle-o"></i> <span>Reports</span><span class="pull-right-container"><small class="label pull-right bg-green">{{ $countRecords['reports'] }}</small></span></a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-pie-chart"></i>
          <span>Charts</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/admin/chart/users"><i class="fa fa-circle-o"></i> Users</a></li>
          <li><a href="/admin/chart/posts"><i class="fa fa-circle-o"></i> Posts</a></li>
        </ul>
      </li>
      <li><a href="/admin/categories"><i class="fa fa-book"></i> <span>Categories</span></a></li>
      <li><a href="/admin/notifications"><i class="fa fa-book"></i> <span>Notifications</span></a></li>
    </ul>