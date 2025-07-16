<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{url('assets/default_web/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/default_web/bootstrap-rtl.min.css')}}" >
    <script src="{{url('assets/default_web/jquery.min.js')}}"></script>
    <script src="{{url('assets/default_web/popper.min.js')}}"></script>
    <script src="{{url('assets/default_web/bootstrap.min.js')}}"></script>
</head>
<body>

<div class="container ">
    @yield('main')
</div>

</body>
</html>
