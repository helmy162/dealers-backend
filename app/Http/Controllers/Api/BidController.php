<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreBidRequest;
use App\Http\Requests\UpdateBidRequest;
use App\Models\Bid;
use App\Models\Auction;
use App\Models\Car;
use App\Events\NewBid;
use App\Notifications\BidNotification;
use Carbon\Carbon;

class BidController extends Controller
{
    //add bid for car
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bid' => 'required|integer',
            'car_id' => 'required|integer',
            'auction_id' => 'required|integer',
        ]);

        $auction = Auction::findOrFail($validated['auction_id']);
        $car = Car::findOrFail($validated['car_id']);
        $bid_amount = $validated['bid'];

        if( $auction->car_id != $car->id ){
            abort('400', 'Car and Auction does not match!');
        }

        if( $auction->end_at < Carbon::now() ){
            abort('400', 'Auction expired!');
        }

        if( $auction->start_at > Carbon::now() ){
            abort('400', 'Auction not started yet!');
        }

        if($bid_amount > auth()->user()->bid_limit){
            return response()->json([
                'success' => false,
                'bid_limit' => auth()->user()->bid_limit,
                'message' => 'Bid limit exceeded!'
            ], 400);
        }

        if( $auction->latestBid && $auction->latestBid->bid + 500 > $bid_amount ){
            return response()->json([
                'success' => false,
                'last_bid' => $auction->latestBid->bid,
                'last_bid_dealer' => $auction->latestBid->dealer->name,
                'next_min_bid' => $auction->latestBid->bid + 500,
                'end_at' => $auction->end_at,
                'message' => 'Not enough bid amount!'
            ], 400);
        }

        if( !$auction->latestBid && $auction->start_price > $bid_amount ){
            return response()->json([
                'success' => false,
                'start_price' => $auction->start_price,
                'end_at' => $auction->end_at,
                'message' => 'Not enough bid amount!'
            ], 400);
        }

        
        if($auction->end_at->diffInSeconds(Carbon::now()) < 60){
            $auction->end_at = Carbon::now()->addSeconds(60);
            $auction->save();
        }
        
        $bid                        = new Bid();
        $bid->user_id               = auth()->user()->id;
        $bid->car_id                = $car->id;
        $bid->auction_id            = $auction->id;
        $bid->bid                   = $bid_amount;
        $bid->save();

        $car->status = 'approved';
        $car->save();

        broadcast(new NewBid([
            'last_bid' => $bid_amount,
            'last_bid_dealer' => auth()->user()->name,
            'next_min_bid' => $bid_amount + 500,
            'end_at' => $auction->end_at
        ], $auction->id))->toOthers();


        //add notification for admin
        // $admin->notify(new BidNotification($car, $user, $request->bid));

        return response()->json([
            'success' => true,
            'last_bid' => $bid_amount,
            'last_bid_dealer' => auth()->user()->name,
            'next_min_bid' => $bid_amount + 500,
            'end_at' => $auction->end_at
        ]);
    }
}
