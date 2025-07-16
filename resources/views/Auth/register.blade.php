@extends('layouts.auth')

@section('title','ایجاد حساب ')

 @section('main')

     <!-- START: Main Content-->
     <div class="container">
         <div class="row vh-100 justify-content-between align-items-center">
             <div class="col-12">
                 <form action="#" class="row row-eq-height lockscreen  mt-5 mb-5">
                     <div class="lock-image col-12 col-sm-5"></div>
                     <div class="login-form col-12 col-sm-7">
                         <div class="form-group">
                             <label>نام   </label>
                             <input type="text" name="name" class="form-control" value="{{old('name')}}">
                         </div>
                         <div class="form-group">
                             <label>نام خانوادگی </label>
                             <input type="text" name="family" class="form-control" value="{{old('family')}}">
                         </div>
                         <div class="form-group">
                             <label>نام کاربری  </label>
                             <input type="text" name="username" class="form-control" value="{{old('username')}}">
                         </div>
                         <div class="form-group">
                             <label> موبایل  </label>
                             <input type="text" name="mobile" class="form-control" value="{{old('mobile')}}">
                         </div>
                         <div class="form-group">
                             <label>تلفن  </label>
                             <input type="text" name="tel" class="form-control" value="{{old('tel')}}">
                         </div>
                         <div class="form-group">
                             <label>آدرس  </label>
                             <input type="text" name="address" class="form-control" value="{{old('address')}}">
                         </div>

                         <div class="form-group">
                             <label>پسورد  </label>
                             <input type="password" name="password" class="form-control" value="{{old('password')}}">
                         </div>
                         <div class="form-group">
                             <label>تکرار پسورد  </label>
                             <input type="password" name="password2" class="form-control" value="{{old('password2')}}">
                         </div>
{{--                         @if(isset(CAPTCHA['register']) && CAPTCHA['register'][0])--}}
{{--                             <script>--}}
{{--                                 $(function () {--}}
{{--                                     $('#captchaRefresh').on('click',function () {--}}
{{--                                         $('#captcha').prop('src',$('#captcha').prop('src')+'1');--}}
{{--                                     })--}}
{{--                                 })--}}
{{--                             </script>--}}
{{--                             <div class="form-group">--}}
{{--                                 <label>کدامنیتی  </label>--}}
{{--                                 <div class="row">--}}
{{--                                     <div class="col-5">--}}
{{--                                         <input type="text" name="captcha" class="form-control">--}}

{{--                                     </div>--}}
{{--                                     <div class="col-7" id="captchaRefresh" style="cursor: pointer">--}}
{{--                                         <img src="{{url('assets/images/refresh-icon.png')}}">--}}
{{--                                         <img id="captcha" class="cap col-xs-5" src="{{route('captcha',['capRegister',CAPTCHA['register'][1],CAPTCHA['register'][2]]).'?i='.rand(1,1000)}}">--}}

{{--                                     </div>--}}
{{--                                 </div>--}}
{{--                             </div>--}}
{{--                         @endif--}}


                         <div class="form-group mb-20">
                             <button class="btn btn-primary" type="submit"> ثبت نام </button>
                         </div>

                         <div class="mt-2">قبلاً حساب دارید؟ <a href="page-login.html">وارد بشوید</a></div>
                     </div>
                 </form>
             </div>

         </div>
     </div>
     <!-- END: Content-->



 @stop
