@extends('layouts.auth')

@section('title','تایید کد دریافتی')

 @section('main')

     <div class="container">
         <div class="row vh-100 justify-content-between align-items-center">
             <div class="col-12">

                 <form action="{{route('forgot_verify')}}" method="post" class="row row-eq-height lockscreen  mt-5 mb-5">
                     @csrf
                     <div class="lock-image col-12 col-sm-5"></div>
                     <div class="login-form col-12 col-sm-7">
                         <p style="color: red">
                             {{session('alert-danger')}}
                             @php(session()->forget('alert-danger'))
                         </p>
                         <div class="form-group mb-3">
                             <label for="username">کد دریافتی از طریق پیامک</label>
                             <input class="form-control" name="code" value="{{old('code')}}" type="text" id="username" required="" placeholder="پسورد جدید دریافتی از طریق پیامک ">
                         </div>
                         @if(isset(CAPTCHA['forgot_verify']) && CAPTCHA['forgot_verify'][0])
                             <script>
                                 $(function () {
                                     $('#captchaRefresh').on('click',function () {
                                         $('#captcha').prop('src',$('#captcha').prop('src')+'1');
                                     })
                                 })
                             </script>
                             <div class="form-group">
                                 <label>کدامنیتی  </label>
                                 <div class="row">
                                     <div class="col-5">
                                         <input type="text" name="captcha" class="form-control">
                                     </div>
                                     <div class="col-7" id="captchaRefresh" style="cursor: pointer">
                                         <img src="{{url('assets/images/refresh-icon.png')}}">
                                         <img id="captcha" class="cap col-xs-5" src="{{route('captcha',['capForgot_verify',CAPTCHA['forgot_verify'][1],CAPTCHA['forgot_verify'][2]]).'?i='.rand(1,1000)}}">
                                     </div>
                                 </div>
                             </div>
                         @endif


                         <div class="form-group mb-20">
                             <button class="btn btn-primary" type="submit">ارسال کدفعالسازی</button>
                         </div>

                         <div class="mt-20"> <a href="{{route('login')}}">ورود به حساب کاربری</a></div>
                     </div>
                 </form>
             </div>

         </div>
     </div>


     @stop
