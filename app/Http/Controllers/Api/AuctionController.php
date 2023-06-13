<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
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
use Illuminate\Support\Facades\Http;

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
            'start_at' => 'required',
            'end_at' => 'required',
            'start_price' => 'required|integer'
        ],[
            'car_id.unique' => 'Car already at auction!'
        ]);

        $car = Car::findOrFail($validated['car_id']);

        $auction                    = new Auction();
        $auction->car_id            = $validated['car_id'];
        $auction->start_price       = $validated['start_price'];
        $auction->start_at          = Carbon::make($validated['start_at']);
        $auction->end_at            = Carbon::make($validated['end_at']);
        $auction->save();

        
        $car->status = 'approved';
        $car->save();

        $data = [
            'make' => $car->details->make,
            'model' => $car->details->model,
            'year' => $car->details->year,
            'mileage' => $car->details->mileage,
            'start_price' => $auction->start_price,
            'start_at' => $auction->start_at,
            'end_at' => $auction->end_at,
            'image' => asset('/storage/car_images/'.$car->images[0])
        ];
        // Sending data to WhatsApp webhook
        $response = Http::post('https://hook.us1.make.com/gwrof533jp0974w43brsjpo6kj3qvekl', $data);

        $dealers = User::whereStatus('active')->whereType('dealer')->whereNotifyNewAuction(true)->get();
        foreach($dealers as $dealer){
            Mail::to($dealer->email)->queue(new newAuction($car, $dealer));
        }

        // send a push notification to subscriped dealers
        $isPushNotificationSent = NotificationController::sendNewAuctionNotification($car); // returns bool


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
            'start_at' => 'required',
            'end_at' => 'required',
            'start_price' => 'required|integer'
        ],[
            'car_id.unique' => 'Car already at auction!'
        ]);
        
        $auction = Auction::findOrFail($id);
        $car = Car::findOrFail($validated['car_id']);

        $auction->start_price       = $validated['start_price'];
        $auction->start_at          = Carbon::make($validated['start_at']);
        $auction->end_at            = Carbon::make($validated['end_at']);
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

        // send a mobile push notification to the winner user, handle the result later
        $isPushNotificationSent = NotificationController::sendWonAuctionNotification($car, $user->id); // returns bool

        return response()->json([
            'success' => true,
            'auction' => $auction
        ]);

    }
}
