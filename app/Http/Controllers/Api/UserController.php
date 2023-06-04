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
        return response()->json(auth()->user()->bids->load('car', 'car.details:id,car_id,make,model,year', 'auction:id,car_id,end_at,winner_bid', 'auction.latestBid:auction_id,bid'));
    }

    public function getOwnOffers(){
        return response()->json(auth()->user()->offers->load('car', 'car.details:id,car_id,make,model,year'));
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

    public function updateProfile(Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.auth()->user()->id,
            'phoneNumber' => 'string',
            'company' => 'string'
        ]);

        
        $user                       = auth()->user();
        $user->name                 = $request->name;
        $user->email                = $request->email;
        $user->phone                = $request->phoneNumber;
        $user->company              = $request->company;
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
