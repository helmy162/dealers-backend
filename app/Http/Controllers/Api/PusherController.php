<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function authenticateUser(Request $request){
        $user_data = json_encode([
            "id" => (string) auth()->user()->id,
            "name" => auth()->user()->name
        ]);
        return response()->json([
            'auth' => env('PUSHER_APP_KEY').':'.hash_hmac('sha256', $request->socket_id.'::user::'.$user_data, env('PUSHER_APP_SECRET')),
            'user_data' => $user_data
        ]);
    }

    public function authorizeChannel(Request $request){
        $string = $request->socket_id.':'.$request->channel_name;

        return response()->json([
            'auth' => env('PUSHER_APP_KEY').':'.hash_hmac('sha256', $string, env('PUSHER_APP_SECRET')),
        ]);
    }
}
