@extends('layouts.auth')


@section('title')
    ورود به حساب کاربری
@endsection
 @section('main')
     <div class="limiter">
         <div class="container-login100 page-background">
             <div class="wrap-login100">
                 <form class="login100-form validate-form" action="{{route('login')}}" method="post">
					@csrf
                     <span class="login100-form-logo">
						<img alt="" src="{{url('')}}/assets/images/loading.png">
					</span>
                     <span class="login100-form-title p-b-34 p-t-27">
						ورود
					</span>

                     <x-validation-error/>

                     <div class="wrap-input100 validate-input" data-validate="نام کاربری خود را وارد کنید">
                         <input class="input100" type="text" name="username" placeholder="نام کاربری" value="{{old('username')}}">
                         <i class="material-icons focus-input1001">person</i>
                     </div>
                     <div class="wrap-input100 validate-input" data-validate="رمز عبور خود را وارد کنید">
                         <input class="input100" type="password" name="password" placeholder="رمز عبور">
                         <i class="material-icons focus-input1001">lock</i>
                     </div>


                     @php
                         $captcha = config('captcha');
                     @endphp

                     @if(isset($captcha['login']) && $captcha['login'][0])
                         <div class="form-group">
                             <div class="row">
                                 <div  class="col-5 validate-input" data-validate="کد امنیتی را وارد کنید">
                                     <input type="text" name="captcha" class="input100" placeholder="کد امنیتی ">
                                 </div>

                                 <div class="col-7" id="captchaRefresh" style="cursor: pointer">
                                     <img src="{{ url('assets/images/refresh-icon.png') }}">
                                     <img id="captcha" class="cap col-xs-5"
                                          src="{{ route('captcha', ['capLogin', $captcha['login'][1], $captcha['login'][2]]) . '?i=' . rand(1, 1000) }}">
                                 </div>
                             </div>
                         </div>
                     @endif


                     <div class="contact100-form-checkbox">
                         <div class="form-check">
                             <label class="form-check-label">
                                 <input name="remember" class="form-check-input" type="checkbox"  checked=""> مرا به خاطر بسپار
                                 <span class="form-check-sign">
									<span class="check"></span>
								</span>
                             </label>
                         </div>
                     </div>
                     <div class="container-login100-form-btn">
                         <button class="login100-form-btn">
                             ورود
                         </button>
                     </div>
{{--                     <div class="text-center p-t-50">--}}
{{--                         <a class="txt1" href="">--}}
{{--                             رمز عبور را فراموش کرده اید؟--}}
{{--                         </a>--}}
{{--                     </div>--}}
                 </form>
             </div>
         </div>
     </div>
@endsection
