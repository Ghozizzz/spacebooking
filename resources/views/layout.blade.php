<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Class</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
  <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet">
  
  <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
  @yield('script')

  <style>
  .hide{
    display:none;
  }

  .show{
    display:block;
  }

  </style>
@yield('css')
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home.index') }}">
        <div class="sidebar-brand-icon">
          <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Class</div>
      </a>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.index') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

       <!-- Nav Item - Dashboard -->
       <li class="nav-item {{ Request::is('master-facility/search') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.master.search') }}">
          <i class="fas fa-search"></i>
          <span>Search Facility</span></a>
      </li>

      @if(session('role') != 1 || session('facilities'))

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('manage/booking*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.booking.index') }}">
          <i class="fas fa-book"></i>
          <span>Booking Management</span></a>
      </li>

      @endif

      <!-- Divider -->
      <hr class="sidebar-divider mb-0">

      @if(session('role') !== 1)

      <div class="sidebar-heading mt-3">
        Data Management
      </div>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('monitor-class*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.monitor.class') }}">
          <i class="fas fa-fw fa-table"></i>
          <span>Class Data</span></a>
      </li>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('master-facility') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.master.facility') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>Facility Data</span></a>
      </li>

       <!-- Nav Item - Dashboard -->
       <li class="nav-item {{ Request::is('master-equipment*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.master.equipment') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>Equipment Data</span></a>
      </li>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('master-facility-equipment*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.master.facility.equipment') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>Facility Equipment Data</span></a>
      </li>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('schedule*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.schedule') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>Schedule Data</span></a>
      </li>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('period*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.master.period') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>Period Data</span></a>
      </li>

      @elseif(!empty(session('facilities')))
        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Request::is('master-facility') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.master.facility') }}">
            <i class="fas fa-fw fa-database"></i>
            <span>Facility Data</span></a>
        </li>
      @endif

      @if(session('role') == 2)

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('user') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.user') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>User Data</span></a>
      </li>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ Request::is('user/relasi_faculty') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home.user.relasi_faculty') }}">
          <i class="fas fa-fw fa-database"></i>
          <span>User Faculty Relation</span></a>
      </li>


       <!-- Divider -->
       <hr class="sidebar-divider">
      @endif


      @if(session('role') == 2)

       <div class="sidebar-heading">
         Administration
       </div>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('home.config') }}">
          <i class="fas fa-cogs"></i>
          <span>Configuration</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider mb-0">
      @endif


      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login.signout') }}">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">


      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        @yield('content')

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <!-- <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer> -->
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Custom scripts for all pages-->
  <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
  <script src="{{asset('js/select2.min.js')}}"></script>

  <script>
  $(document).ready(function() {
    $("label").click(function(event) {
        // this.append wouldn't work
        // $(this).append("");
        var forAttr = "#"+$(this).attr('for')+"-info";
        console.log(forAttr);
        $(".info").removeClass("show");
        $(".info").addClass("hide");
        $(forAttr).removeClass("hide");
        $(forAttr).addClass("show");
    });

    $("#search").click(function(){
      $("#search-result").fadeToggle();
    });

    $(':checkbox[name=allday]').click (function () {
      $('.checkbox-days').prop('checked', this.checked);
    });
});
</script>

</body>

</html>
