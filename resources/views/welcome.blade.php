<!DOCTYPE html>
<html lang="en">

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Leave Management System</title>

  <!-- Font Awesome Icons -->
  <link href="theme/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>

  <!-- Plugin CSS -->
  <link href="theme/vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

  <!-- Theme CSS - Includes Bootstrap -->
  <link href="theme/css/creative.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#">Leave Management System</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto my-2 my-lg-0">
            @if (Route::has('login'))
                {{-- <div class="top-right links"> --}}
                    @auth
                        <li class="nav-item">
                          <a class="nav-link js-scroll-trigger" href="{{ url('/home') }}">Home</a>
                          {{-- <a href="{{ url('/home') }}">Home</a> --}}
                        </li>
                          {{-- <a href="{{ route('login') }}">Login</a> --}}
                    @else
                        <li class="nav-item">
                          <a class="nav-link js-scroll-trigger" href="{{ route('login')}}">Login</a>
                          {{-- <a href="{{ route('login') }}">Login</a> --}}
                        </li>
                        
                        @if (Route::has('register'))
                          <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="{{ route('register') }}">Register</a>
                          </li>
                          {{--   <a href="{{ route('register') }}">Register</a> --}}
                        @endif
                    @endauth
             {{--    </div> --}}
            @endif

        </ul>
      </div>
    </div>
  </nav>

  <!-- Masthead -->
  <header class="masthead">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-10 align-self-end">
          <h1 class="text-uppercase text-white font-weight-bold">Technology should improve your life... not become your life</h1>
          <hr class="divider my-4">
        </div>
        <div class="col-lg-8 align-self-baseline">
          <p class="text-white-75 font-weight-light mb-5"> Billy Cox</p>
          <a class="btn btn-primary btn-xl js-scroll-trigger" href="#" data-toggle="modal" data-target="#exampleModal">Find Out More</a>
          <!-- Button trigger modal -->
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Developer Info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-left">
                  <p><i class='fas fa-smile' style='font-size:18px;color:blue'></i> Chidozie Ihedioha</p> 
                  <p><i class='fas fa-phone' style='font-size:18px;color:blue'></i> 08067709490, 09090693064.</p>
                  <p> <i class='fas fa-envelope' style='font-size:18px;color:blue'></i> ihediohachidozie@gmail.com </p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
<!-- Find out more to show developer -->
 
  <!-- Footer -->
  <footer class="bg-light py-5">
    <div class="container text-center">
      {{-- div class="small text-center text-muted">Copyright &copy; {{ now()->year}} - De Royce Solutions</div> --}}
        <span data-toggle="tooltip" data-placement="right" title="*** Ihedioha Chidozie | +234 (0)806 770 9490 ***" class="font-weight-bold">
          Copyright &copy; {{ now()->year}} </span> 
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="theme/vendor/jquery/jquery.min.js"></script>
  <script src="theme/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="theme/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="theme/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

  <!-- Custom scripts for this template -->
  <script src="theme/js/creative.min.js"></script>

</body>

</html>
