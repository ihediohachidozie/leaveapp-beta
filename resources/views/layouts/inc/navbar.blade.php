<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

    @if (Request::is('home'))

        
      <!-- Nav Item - Messages -->
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <!-- Counter - Messages -->
          @if ($comments->count())
              <span class="badge badge-danger badge-counter"> {{ $comments->count() }}</span>
          @endif
          
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
          <h6 class="dropdown-header">
            Message Center
          </h6>  
          @foreach ($comments as $comment)
            <a class="dropdown-item d-flex align-items-center" href="#">
              @include('layouts.inc.getImage')
              <div class="font-weight-bold">
                <div class="text">{{$comment->comment}}</div>
                <div class="small text-gray-500">@include('layouts.inc.getname')</div>
              </div>
            </a>
            <form id="clear-comment" action="{{route('comment.update', ['comment' => $comment])}}" method="post">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value = "1">
            
            </form>
          @endforeach
          <a class="dropdown-item text-center small text-gray-500" href="#" 
            onclick="event.preventDefault(); 
            document.getElementById('clear-comment').submit();"> 
            Clear Message
          </a> 
        </div>
      </li>
        
    @endif
  
      <div class="topbar-divider d-none d-sm-block"></div>

      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->username}}</span>
          @if (auth()->user()->image)
                                
            <img class="img-profile rounded-circle" src=" {{ asset('storage/'. auth()->user()->image)}}">
          @else
            <img class="img-profile rounded-circle" src=" {{ asset('theme/img/default.jpg')}}">
            
          @endif
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          <router-link to="/storage/user-profile" class="dropdown-item">
              <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
              <span class="menu-title">Profile</span>
          </router-link>

          <a class="dropdown-item" href="#" disabled>
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            Settings
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
            Activity Log
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
          </a>
        </div>
      </li>

    </ul>

  </nav>