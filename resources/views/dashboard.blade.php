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

  <title>Ches' Stars Secondary School</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link rel="icon" href="/storage/img/logo.png" type="image/icon type">
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
    
    @can('isAdmin')
    <!-- SEARCH FORM -->
      <div class="input-group input-group-sm col-6">
        <input class="form-control form-control-navbar" @keyup.enter="loadSearch" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" v-on:click="loadSearch">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    @endcan
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
        <strong class="d-block text-white">User id: {{Auth::user()->username}}</strong>
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
            <router-link to="/admin/dashboard" class="nav-link">
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
                <router-link to="/admin/users" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/admin/staff" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Staff</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/admin/students" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Students</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link to="/admin/examination" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Exam Portal 
              </p>
            </router-link>
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
                <router-link to="/admin/events" class="nav-link">
                  <i class="fas fa-calendar-plus nav-icon"></i>
                  <p>Events</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/notice" class="nav-link">
                  <i class="fas fa-sticky-note nav-icon"></i>
                  <p>Notice</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/teachers" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Class Teacher</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/gallery" class="nav-link">
                  <i class="fas fa-camera-retro nav-icon"></i>
                  <p>Gallery</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/newsfeed" class="nav-link">
                  <i class="fas fa-newspaper nav-icon"></i>
                  <p>News Feed</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/SSLC" class="nav-link">
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
                <router-link to="/admin/statistics" class="nav-link">
                  <i class="fas fa-user-tie nav-icon"></i>
                  <p>Staff</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/allclass" class="nav-link">
                  <i class="fas fa-chalkboard-teacher nav-icon"></i>
                  <p>Class</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/subjectList" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Subject List</p>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/admin/studentvotes" class="nav-link">
                  <i class="fas fa-person-booth nav-icon"></i>
                  <p>Student Vote</p>
                </router-link>
              </li>
            </ul>
          </li>
         
          <li class="nav-item">
            <router-link to="/admin/developer" class="nav-link">
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
            <router-link to="/staff/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt "></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <li class="nav-item">
          <router-link to="/staff/classroom" class="nav-link">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Classroom
              </p>
            </router-link>
          </li> 
          
          <li class="nav-item">
            <router-link to="/staff/examination" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Exam Portal <span class="right badge blink-bg">New</span>
              </p>
            </router-link>
          </li> 

          <li class="nav-item">
            <router-link to="/staff/assignment" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Assignments
              </p>
            </router-link>
          </li>  

          <li class="nav-item">
            <router-link to="/staff/uploadedfiles" class="nav-link">
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

          @can('allowedExam')  
          <li class="nav-item">
            <router-link to="/student/examination" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p> 
                Exam Portal <span class="right badge blink-bg">New</span>
              </p>
            </router-link>
          </li> 
          @endcan
          <!-- end student -->
          @endcan
          <li class="nav-item">
            <router-link to="/tutorials" class="nav-link">
              <i class="nav-icon fas fa-file-video"></i>
              <p>
                Tutorials
              </p>
            </router-link>
          </li>
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
