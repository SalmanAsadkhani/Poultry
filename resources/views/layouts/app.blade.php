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


    @yield('css')
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
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
               aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="index.html">
                <img src="{{url('')}}/assets/images/logo.png" alt="" />
                <span class="logo-name">لوراکس</span>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="pull-right">
                <li>
                    <a href="javascript:void(0);" class="sidemenu-collapse">
                        <i class="material-icons">reorder</i>
                    </a>
                </li>
            </ul>
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
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">feedback</i>بازخورد
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">help</i>راهنما
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">power_settings_new</i>خروج
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- #END# Tasks -->
                <li class="pull-right">
                    <a href="javascript:void(0);" class="js-right-sidebar" data-close="true">
                        <i class="fas fa-cog"></i>
                    </a>
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

<script>
    $('body').on('keypress keyup focus blur','.money',function (event) {
        $(this).val(ToRial(fa2la($(this).val().replace(/[^0-9۰-۹\.]/g,''))));
    });
</script>
@yield('js')
</body>
