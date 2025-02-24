<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB Rangers</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('admin/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('admin/plugins/summernote/summernote-bs4.min.css')}}">

    <!--Inter Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstraps -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
     @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <style>
        .sidebar a {
            color:rgb(255, 255, 255) !important;
        }
        .sidebar a:hover {
            color: #fff !important;
        }
        .user-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            max-width: 200px; /* Adjust width to fit your layout */
        }

        .user-name:hover {
            overflow: visible;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Custom CSS for sidebar content */
        .nav-sidebar .nav-link p {
            white-space: normal;
            word-wrap: break-word;
            overflow: hidden;
            max-width: 100%;
        }

        .sidebar-mini .nav-sidebar .nav-link {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }

        /* Additional styles for better sidebar content readability */
        .nav-sidebar .nav-item {
            width: 100%;
        }

        .sidebar-mini .main-sidebar .nav-link {
            width: 100%;
            overflow: hidden;
        }

        /* Ensure long text doesn't overflow */
        .user-panel .info {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        /* Make brand logo container stretch full width */
        .brand-link {
            width: 100% !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0.8125rem 0.5rem !important;
        }

        /* Ensure the logo text fills the container */
        .brand-text {
            display: block;
            width: 100%;
            color: white !important;
        }

        /* User panel styles */
        .user-panel {
            width: 100%;
        }

        .user-panel .info {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }
    </style>
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
  <img class="animation__shake" src="{{asset('./img/JR1.png')}}" alt="JR1" height="60" width="60">
  </div>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: rgb(0, 37, 68);">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboardA') }}" class="brand-link">
    <img src="{{asset('./img/JRclear.jpg')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">JOB Rangers</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="background-color:rgb(0, 37, 68);">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="{{asset('admin/dist/img/profile_nrnwni.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <span class="d-block user-name" style="color: white;">{{ Auth::user()->name }}</span>
            </div>
        </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> 

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!--Sidebar dashboard yg ada wrna -->
          <li class="nav-header">ADMIN FUNCTIONS</li>

          <!-- List dalam sistem -->

          <li class="nav-item">
            <a href="{{route('admin.dashboardA')}}" class="nav-link"> 
              <i class="nav-icon fas fa-desktop"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="{{route('admin.discover')}}" class="nav-link"> 
              <i class="nav-icon fas fa-search"></i>
              <p>

                Discover Jobs
              </p>
            </a>
          </li> -->

          <li class="nav-item">
            <a href="{{route('admin.jobs.index')}}" class="nav-link"> 
            <i class="fa-fw fas fa-briefcase nav-icon"></i>
              <p>
                Job Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.jobs.create')}}" class="nav-link">
                  <i class="fas fa-edit nav-icon"></i>
                  <p>Create Jobs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.jobs.index')}}" class="nav-link">
                  <i class="fas fa-folder-open nav-icon"></i>
                  <p>Manage Jobs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.jobapplication.index')}}" class="nav-link">
                  <i class="fas fa-business-time nav-icon"></i>
                  <p>Applied Jobs</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{route('admin.jobs.index')}}" class="nav-link"> 
            <i class="fa-fw fas fa-clock nav-icon"></i>
              <p>
                Schedules
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.schedule.index')}}" class="nav-link">
                  <i class="fas fa-clipboard-list nav-icon"></i>
                  <p>Manage Schedules</p>
                </a>
              </li>
            </ul>
          </li>
          
          <!-- User Management -->  
          <li class="nav-item">
            <a href="{{route('admin.user.index')}}" class="nav-link"> 
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                User Management
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('admin.helpdesk.index')}}" class="nav-link"> 
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Helpdesk
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.calendar') }}" class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                <i class="nav-icon far fa-calendar-alt"></i>
                <p>
                    Calendar
                </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.recruiter-requests.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>
                    Recruiter Requests
                    @php
                        $pendingCount = \App\Models\User::where('recruiter_request_status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge badge-warning right">{{ $pendingCount }}</span>
                    @endif
                </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" 
                    class="nav-link" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt" style="color:rgb(255, 37, 37);"></i>
                        <p style="color:rgb(255, 77, 77);"><b>Logout</b></p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- Logout Button in admin.blade.php -->
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

    <!-- Content Wrapper. Contains page content -->
        <!-- CONTENT SEMUA START DEKAT SINI!!!! -->
        <div class="content-wrapper" style="background-color:rgb(232, 241, 255)">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <!-- Left navbar links -->
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                  </li>
              </ul>
          <!--div class utk LAB PROJECT-->
          <div class="container" style="position: relative;"> 
              @yield('content') 
          </div>
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
  </div>

  <footer class="main-footer" style="text-align: center;">
    <strong>Copyright JOB Rangers &copy; 2024 Nurin Ewani Shahudin.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.2.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('admin/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('admin/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('admin/dist/js/pages/dashboard.js')}}"></script>
  <br>
  @yield('scripts')
</body>
</html>

