<?php

// app/Http/Controllers/PushSubscriptionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'endpoint'    => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth'   => 'required|string',
        ]);


        $user = Auth::user();

        $endpoint = $validated['endpoint'];
        $publicKey = $validated['keys']['p256dh'];
        $authToken = $validated['keys']['auth'];


        $user->updatePushSubscription($endpoint, $publicKey, $authToken);


        return response()->json(['success' => true], 200);
    }
}
