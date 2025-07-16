<?php
if(!\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Cookie::get('myUserName')){
    $userCookie = \Illuminate\Support\Facades\Cookie::get('myUserName');
    $ip = $_SERVER['REMOTE_ADDR'];
    if($u = \Illuminate\Support\Facades\DB::connection('mysql2')->select("select * from `users` WHERE  `remember_token` = :userCookie and `last_ip` = :ip ",['userCookie'=>$userCookie,'ip'=>$ip]) ){
       $u = $u[0];
//        dd($u);
        if(!$thisUser = \App\User::where('pk',$u->pk)->first())
            $thisUser = new \App\User();

        $thisUser->pk = $u->pk;
        $thisUser->name = $u->name;
        $thisUser->family = $u->family;
        $thisUser->melli_code = $u->melli_code;
        $thisUser->email = $u->email;
        $thisUser->mobile = $u->mobile;
        $thisUser->tel = $u->tel;
        $thisUser->address = $u->address;
        $thisUser->password = $u->password;
        $thisUser->status = $u->status;
        $thisUser->role = $u->role;
        $thisUser->grade = $u->grade;
        $thisUser->image = $u->image;
        $thisUser->last_ip = $u->last_ip;
        $thisUser->remember_token = $u->remember_token;
        $thisUser->save();

        \Illuminate\Support\Facades\Auth::loginUsingId($thisUser->id);

    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!--..............library-->
    <link type="text/css" rel="stylesheet" href="{{url("assets1/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css")}}">
    <link type="text/css" rel="stylesheet" href="{{url("assets1/lib/bootstrap-4.3.1-dist/bootstrap-rtl.min.css")}}">
    <link type="text/css" rel="stylesheet" href="{{url("assets1/lib/fontawesome-free-5.3.1-web/css/all.min.css")}}">
    <!----plugin-->
    <link type='text/css' rel='stylesheet' href="{{url('assets1/css/plugin/MegaNavbar.css')}}"/>
    <link type='text/css' rel='stylesheet' href="{{url('assets1/css/plugin/owl.carousel.min.css')}}"/>
    <link type='text/css' rel='stylesheet' href="{{url('assets1/css/plugin/owl.theme.default.min.css')}}"/>
    <link type='text/css' rel='stylesheet' href="{{url('assets1/css/plugin/aos.css')}}"/>
    <link type='text/css' rel='stylesheet' href='{{url('assets1/css/plugin/comment.css')}}'/>
    <!---mainCss----->
    <link type='text/css' rel='stylesheet' href="{{url('assets1/css/style.css?v=2')}}"/>
</head>
<body>

<header>
    <div class="top py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-12">

                    <div class="occasion_today" style="display: none">
                        روز برنامه نویسی کامپیوتر
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="date_roday text-left">
                        امروز {{jdate("l d-m-Y ")}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div  class="sticky-top">
        <nav class="navbar navbar-expand-sm navbar-dark sticky-top nav_home mb-4 p-0">
            <div  class="container position-relative">
                <a class="navbar-brand" href="{{url('')}}">
                    <img src="{{url($opt['logo'])}}" class="img-fluid d-block" alt="{{$opt['title']}}">
                </a>
                <button class="navbar-toggler ml-2" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse collapse_nav" id="navbarTogglerDemo02">
                    <ul class="navbar-nav nav_list w-100">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('')}}">
                                صفحه اصلی
                            </a>
                        </li>
                        <li class="nav-item d-md-inline-block d-block dropdown dropdown-short xs-hover">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                مشاهیر
                            </a>
                            <ul class="dropdown-menu HingeUpToDown d-lg-flex">
                                <?php $cats = \App\Category::where('group',1)->get()  ?>
                                @foreach($cats as $value)
                                <li>
                                    <a href="{{route('articles',['id'=>$value->id])}}">
                                        {{$value->name}}
                                    </a>
                                </li>
                                @endforeach
                                {{--<li class="dropdown-right-onclick dropdown2">--}}
                                    {{--<a data-toggle="collapse" data-target="#id_2" class="dropdown-toggle collapsed">--}}
                                        {{--شاعران--}}
                                    {{--</a>--}}
                                    {{--<ul class="dropdown-menu collapse HingeUpToDown2" id="id_2">--}}
                                        {{--<li>--}}
                                            {{--<a href="">--}}
                                                {{--مهندس دهنوی--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="">--}}
                                                {{--مهندس دهنوی--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{----}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                            </ul>
                        </li>
                        {{--<li class="nav-item">--}}
                            {{--<a class="nav-link" href="#">--}}
                                {{--نخبگان--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        <li class="nav-item" style="display: none">
                            <a class="nav-link" href="#">
                                درباره ما
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('contact')}}">
                                تماس با ما
                            </a>
                        </li>

                        <li class="nav-item add_mashahir mr-auto">
                            <a class="nav-link" href="{{route('addGenius')}}">
                                ثبت مشاهیر
                            </a>
                        </li>
                        @if(Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" target="_blank" href="http://my.neyshabur.ir">
                                    {{\Illuminate\Support\Facades\Auth::user()->name.' '.\Illuminate\Support\Facades\Auth::user()->family}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('logout')}}">
                                    خروج
                                </a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link" href="http://my.neyshabur.ir/register/mashahir">
                                    ثبت نام
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('login')}}">
                                    ورود
                                </a>
                            </li>
                            @endif

                        <li class="nav-item dropdown dropdown_search">
                            <a class="nav-link ico dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-search"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left p-2">
                                <form>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="جستجو ...">
                                        <select class="custom-select">
                                            <option>دسته بندی</option>
                                            @foreach($cats as $cat)
                                                <option>
                                                    {{$cat->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn_orang" type="submit">جستجو</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </div>
</header>

@yield('main')

<footer class="mt-5">
    <div class="top py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="about_footer">
                        <div class="title d-flex align-items-center mb-3">
                            <h3 class="info">
                                درباره ما
                            </h3>
                            <div class="logo mr-auto">
                                <a href="">
                                    <img src="{{url($opt['logo2'])}}" class="img-fluid d-inline-block Max_Img" alt="">
                                </a>
                            </div>
                        </div>
                        <p class="article text-justify">
                            {{$opt['footerDesc']}}
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="contact_footer">
                        <h3 class="title_f mb-3">
                            روابط عمومی سامانه مشاهیر
                        </h3>
                        <ul class="contact mb-4">
                            <li>
                                <i class="fa fa-map-marker"></i>
                                نشانی :
                                &nbsp;<span class="main">
                                              {{$opt['address']}}
                                        </span>
                            </li>
                            <li>
                                <i class="fa fa-phone"></i>
                                تلفن :
                                &nbsp; <span class="main">
                                             {{$opt['phone']}}
                                        </span>
                            </li>
                            <li>
                                <i class="fa fa-fax"></i>
                                فکس :
                                &nbsp;<span class="main">
                                           {{$opt['phone']}}
                                        </span>
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>
                                ایمیل :
                                &nbsp;<span class="main">
                                            {{$opt['email']}}
                                        </span>
                            </li>
                        </ul>
                        <ul class="list_ico d-flex align-items-center mb-3">
                            <li>
                                <a>
                                    <i class="fa fa-paper-plane"></i>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <i class="fab fa-google"></i>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a>
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 col-sm-6 col-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="link_footer">
                                <h3 class="title_f mb-3">
                                    لینک ها
                                </h3>
                                <ul class="list">
                                    @for($i=1;$i<=7;$i++)
                                        <li>
                                            <a href="{{$opt['footerLink'.$i]}}">
                                                {{$opt['footerTxt'.$i]}}
                                            </a>
                                        </li>
                                    @endfor

                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="link_footer">
                                <h3 class="title_f mb-3">
                                    لینک ها
                                </h3>
                                <ul class="list">
                                    <li>
                                        <a>
                                            صفحه نخست
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            درباره ما
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            تماس با ما
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            سوالات
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            تماس با ما
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            سوالات
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom py-2 text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-12">
                    <p class="developer">
                        {{$opt['copyright']}}
                    </p>
                </div>
                <div class="col-md-6 col-12">
                    <ul class="link d-flex justify-content-end">
                        <li>
                            <a>
                                صفحه نخست
                            </a>
                        </li>
                        <li>
                            <a>
                                درباره ما
                            </a>
                        </li>
                        <li>
                            <a>
                                تماس با ما
                            </a>
                        </li>
                        <li>
                            <a>
                                سوالات
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<p id="back-top">
    <a href="#top"><i class="fa fa-arrow-up"></i></a>
</p>
<!--.....library...-->
<script src="{{url('assets1/lib/jquery-2.0.3/jquery-2.0.3.min.js')}}"></script>
<script src="{{url("assets1/lib/bootstrap-4.3.1-dist/popper.min.js")}}"></script>
<script src="{{url('assets1/lib/bootstrap-4.3.1-dist/js/bootstrap.min.js')}}"></script>
<!----plugin---->
<script src="{{url('assets1/js/plugin/owl.carousel.min.js')}}"></script>
<script src="{{url('assets1/js/plugin/aos.js')}}"></script>
<script src="{{url('assets1/js/plugin/comment.js')}}"></script>
<!---mainJs----->
<script src="{{url('assets1/js/incloud.js')}}"></script>

<script>
    var audio;
    $('#addMusic').on('click',function (e) {
        e.preventDefault();
        if($(this).hasClass('act')){
            audio.pause();
            $(this).removeClass('act').prop('src',"{{url('assets1/speaker-gif-animation.gif')}}");
            return;
        }
        audio = new Audio("{{$opt['homeTxt9']}}");
        $(this).addClass('act').prop('src',"{{url('assets1/speaker.jpg')}}");

        audio.play();
    });
    var __u = "{{route('setAnalytics')}}";var ___d = "lang=fa&_token={{csrf_token()}}&page="+location.href;$.ajax({url : __u, type:"POST", data:___d});

</script>
@if(Session::has('alert-success') || Session::has('alert-danger'))
    <link href="{{url('assets/js/bundles/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
    <script src="{{url('assets/js/bundles/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script>
        @if(Session::has('alert-success'))
swal({
            position: 'top-end',
            type: 'success',
            title: '{{ Session::pull('alert-success') }}',
            showConfirmButton: true,
            timer: 4500,
            animation: true
        });
        @endif

        @if(Session::has('alert-danger'))
swal({
            position: 'top-end',
            type: 'error',
            title: '{!! Session::pull('alert-danger') !!} ',
            showConfirmButton: true,
            timer: 4500,
            animation: true
        });
        @endif
    </script>
@endif

@yield('js')
</body>
</html>