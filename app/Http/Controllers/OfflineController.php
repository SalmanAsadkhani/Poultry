<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfflineController extends Controller
{
    public function handle(Request $request)
    {
        $routeName = $request->input('route');
        $method = strtolower($request->input('method', 'post'));
        $payload = $request->input('payload', []);


        $subRequest = Request::create(route($routeName), $method, $payload);
        $subRequest->headers->set('X-CSRF-TOKEN', csrf_token());

        return app()->handle($subRequest);
    }
}
