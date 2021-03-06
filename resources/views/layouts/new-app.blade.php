<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="{{ asset('img/pln.jpg') }}">
    <title>SPB</title>

    <!-- Bootstrap from dashboard template CSS -->
    <link href="{{ asset('admin/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('admin/dashboard.css')}}" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Server PLN Banten Utara</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            @if(Auth::user())
              <li><a href="#your-name">{{Auth::user()->name}}</a></li>
            @endif
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="{{ route('public.file') }}">Pencarian data</a></li>
            @if(!Auth::user())
            <li><a href="{{ route('login') }}">Login</a></li>
            @endif
          </ul>
          @if(Auth::user())
          <ul class="nav nav-sidebar">
            <li><a href="{{ route('bidang.index') }}">Bidang</a></li>
            <li><a href="{{ route('folder.index') }}">Folder</a></li>
            <li><a href="{{ route('file.index') }}">File</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="{{ route('home') }}">Profile</a></li>
            <li><a href="#"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">Logout</a></li>
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                             {{ csrf_field() }}
                         </form>
          </ul>
          @endif
        </div>

        {{--Mulai dari sini--}}
          @yield('content')
        {{--Selesai di sini--}}

      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="{{ asset('admin/bootstrap.min.js')}}"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="{{ asset('admin/holder.min.js')}}"></script>
  </body>
</html>
