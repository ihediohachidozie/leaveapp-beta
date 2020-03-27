<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-globe-africa"></i>
      </div>
      <div class="sidebar-brand-text mx-1">Leave Mgt Sys</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
      <router-link to="/home" class="nav-link">
      
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </router-link>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    
    @if (auth()->user()->category_id)
      <!-- Heading -->
      <div class="sidebar-heading text-white">
        User Menu
      </div>
        
      <!-- Nav Item - Category -->
      <li class="nav-item">
          <router-link to="/user-leave" class="nav-link">
            {{--  <i class="fas fa-bed"></i> --}}
              <i class="fas fa-plane-departure"></i>
              <span class="menu-title">Leave Applications</span>
          </router-link>
      </li>
        <li class="nav-item">
            <router-link to="/user-leave-summary" class="nav-link">
                <i class="fas fa-hot-tub"></i>
                {{-- <i class="fas fa-plane-departure"></i> --}}
                <span class="menu-title">Leave Summary</span>
            </router-link>
        </li>
      @if (auth()->user()->permission == 1)
          
        <li class="nav-item">
            <router-link to="/approval-requests" class="nav-link">
                <i class="fas fa-bed"></i>
                {{-- <i class="fas fa-plane-departure"></i> --}}
                <span class="menu-title">Approval Requests</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link to="/approval-staff-summary" class="nav-link">
                <i class="fas fa-users"></i>
                {{-- <i class="fas fa-plane-departure"></i> --}}
                <span class="menu-title">Staff Summary</span>
            </router-link>
        </li>
      @endif
    @else
      @if (auth()->id() != 1)
          
        <div class="sidebar-heading text-white">
          User Menu In-active
        </div>
        <!-- Divider -->
        <hr class="sidebar-divider">
      @endif
    @endif

    

    @if (auth()->id() == 1)
        
      <!-- Heading -->
      <div class="sidebar-heading text-white">
        Administrative menu
      </div>

      <!-- Nav Item - Category -->
      <li class="nav-item">
          <router-link to="/categories" class="nav-link">
              <i class="fas fa-fw fa-th-list"></i>
              <span class="menu-title">Staff Categories</span>
          </router-link>
      </li>
      <!-- Nav Item - Department -->
      <li class="nav-item">
          <router-link to="/departments" class="nav-link">
              <i class="fas fa-fw fa-building"></i>
              <span class="menu-title">Departments</span>
          </router-link>
      </li>
      <!-- Nav Item - Public Holidays -->
      <li class="nav-item">
          <router-link to="/publicholidays" class="nav-link">
              <i class="fas fa-holly-berry"></i>
              <span class="menu-title">Public Holidays</span>
          </router-link>
      </li>

      <!-- Nav Item - Users -->
      <li class="nav-item">
          <router-link to="/all-users" class="nav-link">
              <i class="fas fa-users-cog"></i>
              <span class="menu-title">Users</span>
          </router-link>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
        <div class="sidebar-heading text-white">
          Leave Menu
        </div>
      <!-- Nav Item - Leave settings -->
      <li class="nav-item">
          <router-link to="/approved-leave" class="nav-link">
              <i class="fas fa-bed"></i>
              <span class="menu-title">Approved Leave</span>
          </router-link>
      </li>
      <li class="nav-item">
          <router-link to="/all-user-leave-summary" class="nav-link">
              <i class="fas fa-hot-tub"></i>
              <span class="menu-title">Leave Summary</span>
          </router-link>
      </li>

    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>