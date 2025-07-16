<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>@yield('title')</title>
    <link rel="icon" href="{{url('assets/images/favicon.ico')}}" type="image/x-icon">
    <link href="{{url('assets/css/app.min.css')}}" rel="stylesheet">
    <link href="{{url('assets/js/bundles/materialize-rtl/materialize-rtl.min.css')}}" rel="stylesheet">
    <link href="{{url('assets/css/style.css')}}" rel="stylesheet" />
    <link href="{{url('assets/css/pages/extra_pages.css')}}" rel="stylesheet" />
</head>
<body class="login-page rtl">

@yield('main')

<script src="{{url('assets/js/app.min.js')}}"></script>
{{--<script src="{{url('assets/js/pages/examples/pages.js')}}"></script>--}}


@yield('js')
</body>


</html>