<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return response()->json(auth()->user());
    }

    public function getOwnBids(){
        return response()->json(auth()->user()->bids->load('car', 'car.details', 'auction', 'auction.latestBid'));
    }

    public function getOwnOffers(){
        return response()->json(auth()->user()->offers->load('car', 'car.details'));
    }

    public function updateNotifications(Request $request){
        $validated = $request->validate([
            'notify_new_auction' => 'required|boolean',
            'notify_won_auction' => 'required|boolean'
        ]);

        $user = auth()->user();

        $user->notify_new_auction = $validated['notify_new_auction'];
        $user->notify_won_auction = $validated['notify_won_auction'];
        $user->save();


        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
