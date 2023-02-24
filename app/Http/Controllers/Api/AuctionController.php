<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Auction;
use App\Models\Car;
use App\Models\Bid;
use App\Models\User;
use App\Mail\newAuction;
use App\Mail\wonAuction;
use Mail;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => Auction::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|integer|unique:auctions',
            'date' => 'required',
            'duration' => 'required',
            'start_price' => 'required|integer'
        ],[
            'car_id.unique' => 'Car already at auction!'
        ]);

        $car = Car::findOrFail($validated['car_id']);

        $duration = CarbonInterval::make($validated['duration']);
        
        $start_at = Carbon::make($validated['date']);

        $auction                    = new Auction();
        $auction->car_id            = $validated['car_id'];
        $auction->start_price       = $validated['start_price'];
        $auction->duration          = $validated['duration'];
        $auction->start_at          = $start_at;
        $auction->end_at            = $start_at->addSeconds($duration->totalSeconds);
        $auction->save();

        
        $car->status = 'approved';
        $car->save();

        $dealers = User::whereStatus('active')->whereType('dealer')->whereNotifyNewAuction(true)->get();
        foreach($dealers as $dealer){
            Mail::to($dealer->email)->send(new newAuction($car, $dealer));
        }

        return response()->json([
            "success" => true,
            "auction" => $auction
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auction = Auction::findOrFail($id);
        return response()->json(['data' => $auction]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'car_id' => 'required|integer|unique:auctions,car_id,'.$id,
            'date' => 'required',
            'duration' => 'required',
            'start_price' => 'required|integer'
        ],[
            'car_id.unique' => 'Car already at auction!'
        ]);
        
        $auction = Auction::findOrFail($id);
        $car = Car::findOrFail($validated['car_id']);

        $duration = CarbonInterval::make($validated['duration']);
        
        $start_at = Carbon::make($validated['date']);

        $auction->car_id            = $validated['car_id'];
        $auction->start_price       = $validated['start_price'];
        $auction->duration          = $validated['duration'];
        $auction->start_at          = $start_at;
        $auction->end_at            = $start_at->addSeconds($duration->totalSeconds);
        $auction->save();

        
        $car->status = 'approved';
        $car->save();

        return response()->json([
            "success" => true,
            "auction" => $auction
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auction = Auction::findOrFail($id);
        
        $car = Car::findOrFail($auction->car_id);
        $car->status = 'pending';
        $car->save();

        $auction->delete();
        $auction->bids()->delete();
        

        return response()->json([
            "success" => true,
            "message" => 'Auction deleted Successfully!'
        ]);
    }

    public function declareWinner(Request $request){
        $validated = $request->validate([
            'auction_id' => 'required|integer',
            'user_id' => 'required|integer',
            'bid_id' => 'required|integer',
        ]);

        $auction = Auction::findOrFail($validated['auction_id']);
        $bid = Bid::findOrFail($validated['bid_id']);
        $user = User::findOrFail($validated['user_id']);
        $car = Car::findOrFail($auction->car_id);

        if( $bid->user_id != $user->id ){
            abort(400, 'Bid and user not matched!');
        }

        if($bid->car_id != $auction->car_id || $bid->auction_id != $auction->id){
            abort(400, 'Auction and bid not matched!');
        }

        $auction->winner_bid = $bid->id;
        $auction->save();

        if($user->notify_won_auction){
            Mail::to($user->email)->send(new wonAuction($car, $user));
        }

        return response()->json([
            'success' => true,
            'auction' => $auction
        ]);

    }
}
