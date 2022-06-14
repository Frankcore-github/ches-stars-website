
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Ches Stars') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div id="app" class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    @can('notAdmin') 
    <!-- Right navbar links -->
    <notification-bell></notification-bell>
    @endcan
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <logo-img value="{{ucwords(Auth::user()->usertype)}}"></logo-img>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <profile-image></profile-image>  
        <div class="info">
        <a href="#" class="d-block">User id: {{Auth::user()->username}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
           <!-- ************************************************************************************ for admin ***********************************************************************-->
          @can('isAdmin') 
          <li class="nav-item">
            <router-link to="/admindashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt "></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Profiles
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/staff" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Staff</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/students" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Students</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Pubic View
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/events" class="nav-link">
                  <i class="fas fa-calendar-plus nav-icon"></i>
                  <p>Events</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/notice" class="nav-link">
                  <i class="fas fa-sticky-note nav-icon"></i>
                  <p>Notice</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/teachers" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Class Teacher</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/gallery" class="nav-link">
                  <i class="fas fa-camera-retro nav-icon"></i>
                  <p>Gallery</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/newsfeed" class="nav-link">
                  <i class="fas fa-newspaper nav-icon"></i>
                  <p>News Feed</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/SSLC" class="nav-link">
                  <i class="fas fa-graduation-cap nav-icon"></i>
                  <p>SSLC</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Classroom
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/subjectList" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Subject List</p>
                </router-link>
              </li>
            </ul>
          </li>
         
          <li class="nav-item">
            <router-link to="/developer" class="nav-link">
              <i class="nav-icon fas fa-cogs "></i>
              <p>
                Developers
              </p>
            </router-link>
          </li>  

          <!-- end admin -->
          @endcan

          <!-- ************************************************************************************ for Staff ************************************************************************************************-->
          @can('isStaff')
          <li class="nav-item">
            <router-link to="/staffdashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt "></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <li class="nav-item">
          <router-link to="/classroom" class="nav-link">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Classroom
              </p>
            </router-link>
          </li> 
          
          <li class="nav-item">
            <router-link to="/assignment" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Assignments
              </p>
            </router-link>
          </li>  

          <li class="nav-item">
            <router-link to="/uploadedfiles" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Uploaded Files
              </p>
            </router-link>
          </li> 
          @endcan
          <!-- end staff -->

          <!-- ************************************************************************** for student *************************************************************************** -->
          @can('isStudent')   
          <li class="nav-item">
            <router-link to="/student/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt "></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/student/classroom" class="nav-link">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Classroom
              </p>
            </router-link>
          </li> 
          <li class="nav-item">
            <router-link to="/student/assignment" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Assignments
              </p>
            </router-link>
          </li> 
          <!-- end student -->
          @endcan
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt "></i>
              <p>
                {{ __('Logout') }}
              </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
        <!-- vue component -->
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="container text-center">
			<strong>Copyright &copy; 2019 Ches' Stars Secondary School, Jowai.</strong><br> All rights reserved.
    </div>
  </footer>
</div>
<!-- ./wrapper -->
@auth
    <script>
      window.user = @json(auth()->user())
    </script>
@endauth
</body>
</html>
