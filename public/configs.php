<?php

define('ROOT',__DIR__.'/');
define('SITE_NAME','آرمان برتر');
define('LOGO','http://localhost/blog/assets/images/logo.png');
define('DEFAULT_LANG',['fa','فارسی']);

define('LANGUAGES',[
    'en'=>['en','انگلیسی'],
]);

define('MENUS',[
    ['منوی اصلی','1'],

]);

define('CAPTCHA',[
//    'login'=>['use','count','difficulty'],
    'login'=>[true,3,1],
    'register'=>[true,3,1],
    'register_verify'=>[true,3,1],
    'forgot'=>[true,3,1],
    'forgot_verify'=>[true,3,1],
]);
