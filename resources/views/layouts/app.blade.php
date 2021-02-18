<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('public/images/favicon.png') }}" type="image/x-icon" />
    <!-- Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" ></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css?') }}" rel="stylesheet">
    <link href="{{-- asset('css/function.css?ver=1.2') --}}" rel="stylesheet">
    <link href="https://vkapsprojects.com/AAS-web-portal/css/function.css?ver=<?php echo rand(99,100000) ?>" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- boot -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">

    <!-- recaptcha  -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    {!! NoCaptcha::renderJs() !!}

    <style type="text/css">
        .btn-light.delete-confirm{background-color: #e3342f!important;color: #fff!important}
        .btn-light.delete-confirm a i{color: #fff!important}
        .btn-light.company_data{background-color: #3490dc!important;color: #fff;}
        .btn-light.pm_data{background-color: #3490dc!important;color: #fff;}
        .btn-light.project_data{background-color: #3490dc!important;color: #fff;}
        .table-hover .table-dark:hover {background-color: #343a40;}
        body{font-family: Arial, Helvetica, sans-serif;color: #4d4d4f;font-size: 13px;}
        .navbar-light .navbar-nav .nav-link{font-weight: bold;color: #777;font-size: 13px;
        	text-transform: uppercase;padding-left: 15px;}
        .mainLogoWeb{max-width: 240px;margin-bottom: 0px;margin-top: -7px;margin-left: -16px;}
    </style>
</head>
<style type="text/css">
    .dropdown-item i {
    display: inline-block;
    width: 20px;
    margin-right: 10px;
    margin-left: -10px;
    color: #c8ced3;
    text-align: center;
}
</style>
<body>
    <div id="loading">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="30" stroke="#ffffff" stroke-width="10" fill="none"></circle>
            <circle cx="50" cy="50" r="30" stroke="#494949" stroke-width="8" stroke-linecap="round" fill="none">
            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;180 50 50;720 50 50" keyTimes="0;0.5;1"></animateTransform>
            <animate attributeName="stroke-dasharray" repeatCount="indefinite" dur="1s" values="18.84955592153876 169.64600329384882;94.2477796076938 94.24777960769377;18.84955592153876 169.64600329384882" keyTimes="0;0.5;1"></animate>
        </circle>
        </svg>
    </div>
    <div style="min-height: 710px;" id="app">

        <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{-- config('app.name', 'Laravel') --}}
                    <img src="{{ asset('public/images/site-logo.jpeg') }}" class="img-responsive mainLogoWeb">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                @auth
                @if (Auth::user()->hasRole('Admin'))
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.all-company') }}">{{ __('Customers') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.all-projects') }}">{{ __('Projects') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.all-project-manager') }}">{{ __('Project Managers') }}</a>
                        </li>

                    </ul>
                @elseif (Auth::user()->hasRole('Staff'))
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.all-project-manager') }}">{{ __('Project Manager') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.all-company') }}">{{ __('Company') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.all-projects') }}">{{ __('Projects') }}</a>
                        </li>
                    </ul>
                @elseif (Auth::user()->hasRole('ProjectManager'))
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pm.view-projects') }}">{{ __('Projects') }}</a>
                        </li>
                    </ul>
                @elseif (Auth::user()->hasRole('Company'))
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __('Project Manager') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __('Projects') }}</a>
                        </li>
                    </ul>
                @endif
                @endauth
                    <!-- Right Side Of Navbar -->
                    @if(!Auth::check())
                    <ul class="navbar-nav ml-auto">
                    @else
                         <ul class="navbar-nav">
                    @endif

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('sign-up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a href="{{ route('change-my-profile') }}" class="dropdown-item"><i class="fa fa-user"></i> Profile</a>
                                    <a href="{{ route('change-password') }}" class="dropdown-item"><i class="fa fa-key"></i> Password</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <div class="bg-white">
        <p class="p-4 text-center text-dark mb-0">
            Phone: (817) 572-0018 | Email: Sales@AdvancedArchitecturalStone.com | Advanced Architectural Stone (AAS)
        </p>
    </div>
<!-- create company Modal by project manager -->

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Customer</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body table-responsive">
        <form>
          @csrf
          <p class="text-danger text-center font-weight-bolder" id="getComapanyNameMsg"></p>
          <div class="form-group row">
              <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Customer Name') }}</label>

              <div class="col-md-6">
                <input id="getComapanyName" type="text" class="form-control" name="company" value="{{ old('company') }}">
              </div>
            </div>

            <!-- <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Owner Name') }}</label>

              <div class="col-md-6">
                <input id="getOwnerName" type="text" class="form-control" name="name" value="{{ old('name') }}">
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail address') }}</label>

              <div class="col-md-6">
                <input id="getEmailaddress" type="email" class="form-control" name="company_email">
                <p id="emailMsg" class="text-danger"></p>
              </div>
            </div>

            <div class="form-group row">
              <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

              <div class="col-md-6">
                <textarea id="getAddress" type="text" class="form-control" name="company_address"></textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="contact" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

              <div class="col-md-6">
                <input id="getPhone" type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{ old('contact') }}">
              </div>
            </div> -->

          <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
              <button type="button" onclick='createCompany()' class="btn btn-sm btn-primary">
                {{ __('Create Customer') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- validation liberary -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript" src="{{ asset('js/validation-liberary/jquery.validate.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('js/validation-liberary/additional-methods.min.js') }}" ></script>
<!-- Scripts -->
<script src="https://vkapsprojects.com/AAS-web-portal/js/function.js?ver=<?php echo rand(99,100000) ?>" ></script>
<script src="https://vkapsprojects.com/AAS-web-portal/js/validation.js?ver=<?php echo rand(99,100000) ?>" ></script>
    <!-- <script src="{{ asset('js/function.js?ver=1.4.73') }}" ></script> -->
    <!-- boot -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js" defer="" async=""></script>
    <script type="text/javascript">
        let base_path = "{{url('/')}}";
    </script>
</body>
</html>
