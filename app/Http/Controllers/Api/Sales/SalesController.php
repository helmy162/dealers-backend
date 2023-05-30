<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\User;

class SalesController extends Controller
{
    public function showCar(Request $request, $id){
        $car = Car::findOrFail($id)
        ->load([
            'details:id,car_id,make,model,year,seller_price',
            'seller:id,name',
            'inspector:id,name',
            'auction',
            'auction.bids',
            'auction.latestBid',
            'auction.bids.dealer:id,name',
            'offers',
            'offers.dealer:id,name'
        ]);

        return response()->json([
            'car' => $car
        ]);
    }

    public function showAllCars(){
        $cars = Car::whereNotNull([
            'details_id',
            'history_id',
            'engine_id',
            'steering_id',
            'interior_id',
            'specs_id',
            'wheels_id',
            'exterior_id',
            'seller_id'
            ])->with([
                'details:id,car_id,make,model,year',
                'seller:id,name',
                'auction:car_id,start_at,end_at',
                'inspector:id,name'
            ])->get();

        return response()->json($cars);
    }

    public function showAllDealers(){
        $users = User::whereType('dealer')->latest()->select('id', 'name', 'email', 'phone', 'bid_limit', 'type')->get();

        return response()->json($users);
    }
}
