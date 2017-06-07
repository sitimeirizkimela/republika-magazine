<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Collapsed Sidebar Layout</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets-admin/adminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets-admin/adminLTE/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets-admin/adminLTE/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url() ?>/assets-admin/adminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url() ?>/assets-admin/adminLTE/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/jquery.browser.min.js"></script>
<script type="text/javascript" src="http://epaper.republika.co.id/assets/js/jquery.blockUI.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.datepick.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tinybox.js"></script>
 <link rel="stylesheet" href="<?php echo base_url() ?>/assets-admin/adminLTE/plugins/datatables/dataTables.bootstrap.css">
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>K</b>DI</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Kedai</b>ROL</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <ul class="dropdown-menu">
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

<?php  $lv = $this->session->userdata('adminLevel');  ?>

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
    <ul class="sidebar-menu">
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
       <?php if($lv) { ?>
      <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
          <a href="/admin/main/home">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
   
        </li>
       <?php } ?>
      <?php if(in_array($lv, array('root','kedai'))) { ?>
      
        <li class="treeview active">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Gerai</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          
          <ul class="treeview-menu">
            <li><a href="/admin/konfirmasi/order"> Order</a></li>
            <li><a href="/admin/konfirmasi/product"> Produk</a></li>
            <li><a href="/admin/konfirmasi/package"> Package</a></li>
            <li><a href="/admin/konfirmasi/subscribe"> Subscribe</a></li>
            <li><a href="/admin/konfirmasi/index"> Konfirmasi</a></li>
            <li><a href="/admin/konfirmasi/voucher"> Voucher</a></li>
          </ul>
        </li>
        <li class="treeview active">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Statis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
            <ul class="treeview-menu">
              <li><?php echo anchor('admin/statis/index', 'Statis'); ?></li>
              <li><?php echo anchor('admin/statis/add', 'Buat Statis'); ?></li>
            </ul>
          </li>  

      <?php } ?>
<!--
        <li class="treeview active">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Epaper</span>
           </a>
          <ul class="treeview-menu">
            <li><a href="/admin/epaper/upload"> Upload</a></li>
            <li><a href="/admin/epaper/get_master"> Index</a></li>
          </ul>
        </li>

-->
    <?php if(in_array($lv, array('root','pusdok'))) { ?>

      <li class="treeview active">
          <a href="#">
            <i class="fa fa-edit"></i>
            <span>Pusat Data</span>
           </a>
          <ul class="treeview-menu">
            
            <li><a href="/admin/news/"> Index</a></li>
            <li><a href="/admin/news/add"> Write </a></li>
            <li><a href="/admin/kategori/"> Index Kategori</a></li>
            <li><a href="/admin/kategori/add"> Write Kategori</a></li>
          </ul>
        </li>
        <?php } ?>
         <?php if(in_array($lv, array('root','kedai','produk'))) { ?>
     
    <li class="treeview active">
          <a href="#">
            <i class="fa fa-edit"></i>
            <span>Product</span>
           </a>
          <ul class="treeview-menu">
            
            <li><a href="/admin/product/"> Index</a></li>
            <li><a href="/admin/product/add"> Write</a></li>
          </ul>
        </li>
        <?php } ?>
        <?php if(in_array($lv, array('root'))) { ?>
     
       <li class="treeview active">
          <a href="#">
            <i class="fa fa-edit"></i>
            <span>User</span>
           </a>
          <ul class="treeview-menu">
            
            <li><a href="/admin/user/"> Index </a></li>
            <li><a href="/admin/user/add"> Write </a></li>
          </ul>
        </li>
        <?php } ?>
         <li class="active treeview">
          <a href="/admin/main/logout">
            <i class="fa fa-exit"></i> <span>Logout</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
   
        </li>
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->
