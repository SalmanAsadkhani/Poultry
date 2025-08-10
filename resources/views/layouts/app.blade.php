<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" href="{{url('')}}/assets/images/favicon.ico" type="image/x-icon">
    <!-- Plugins Core Css -->
    <link href="{{url('')}}/assets/css/app.min.css" rel="stylesheet">
    <link href="{{url('')}}/assets/js/bundles/materialize-rtl/materialize-rtl.min.css" rel="stylesheet">
    <!-- Custom Css -->
    <link href="{{url('')}}/assets/css/style.css" rel="stylesheet" />
    <!-- Theme style. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{url('')}}/assets/css/styles/all-themes.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <link rel="manifest" href="{{url('')}}/manifest.json">

    <meta name="theme-color" content="#4A90E2"/>

    <style>
        @yield('css')
    </style>

</head>
<body class="light rtl">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30">
            <img class="loading-img-spin" src="{{url('')}}/assets/images/loading.png" width="20" height="20" alt="admin">
        </div>
        <p>لطفا صبر کنید...</p>
    </div>
</div>

<div class="overlay"></div>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand " >
                <img src="{{url('')}}/assets/images/logo.png" alt="" />
                    <span class="logo-name"></span>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user_profile">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <img src="{{url('')}}/assets/users/male-icon.jpg" width="32" height="32" alt="User">
                    </a>

                    <ul class="dropdown-menu pullDown">
                        <li class="body">
                            <ul class="user_dw_menu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">person</i>پروفایل
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('logout')}}">
                                        <i class="material-icons">power_settings_new</i>خروج
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->

    <x-sidebar/>

    @yield('main')
<!-- Plugins Js -->

<script src="{{url('')}}/assets/js/app.min.js"></script>
<script src="{{url('')}}/assets/js/chart.min.js"></script>
<!-- Custom Js -->
<script src="{{url('')}}/assets/js/admin.js"></script>
<script src="{{url('')}}/assets/js/pages/index.js"></script>
<script src="{{url('')}}/assets/js/pages/charts/jquery-knob.js"></script>
<script src="{{url('')}}/assets/js/pages/sparkline/sparkline-data.js"></script>
<script src="{{url('')}}/assets/js/pages/medias/carousel.js"></script>

<script src="{{url('')}}/assets/js/offline-sync.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    window.csrfTokenUrl = '{{ url("/csrf-token") }}';
</script>

<x-toast-r/>

<x-service-worker/>

<x-service-js/>

@yield('js')
</body>
