<?php

namespace App\Http\Controllers;


class PwaController extends Controller
{
    public function serviceWorker()
    {
        return response()
            ->view('pwa.sw')
            ->header('Content-Type', 'application/javascript');
    }
}
