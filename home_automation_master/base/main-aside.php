
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../public/dist/img/<?php echo $user_image; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $user_fname; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        
        <?php if ($user_type == "Administrator"): ?>
          <li><a href="/home_automation"><i class="fa fa-sliders"></i> <span>Dashboard</span></a></li>
          <li><a href="../consumptions/"><i class="fa fa-calendar"></i> <span>Consumptions</span></a></li>
          <li class="active treeview">
          <a href="#">
            <i class="fa fa-cog"></i> <span>Setup</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          
            <li><a href="../devices/"><i class="fa fa-wrench"></i> Devices</a></li>
            <li><a href="../users/"><i class="fa fa-user"></i> Users</a></li>
            <li><a href="../locations/"><i class="fa fa-location-arrow"></i> Locations</a></li>
          </ul>
        </li>
        <?php endif ?>
        
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
