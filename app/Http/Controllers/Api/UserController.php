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
        $bids = auth()->user()->bids()
            ->with('car', 'car.details:id,car_id,make,model,year,mileage,engine_size,registered_emirates', 'auction:id,car_id,end_at,winner_bid', 'auction.latestBid:auction_id,bid')
            ->get()
            ->groupBy('car_id')
            ->map(function ($group) {
                return $group->last(); // return only last bid for each car
            });

        return response()->json($bids->values());
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
}
