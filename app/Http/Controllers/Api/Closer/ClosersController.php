<?php

namespace App\Http\Controllers\Api\Closer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Seller;

class ClosersController extends Controller
{
    public function showCar(Request $request, $id){
        $car = Car::findOrFail($id)->makeVisible(['id_images','vin_images','insurance_images','registration_card_images'])
        ->load([
            'details:id,car_id,make,model,year,seller_price',
            'seller:id,name,email,phone',
            'inspector:id,name',
            'auction:id,car_id,end_at,start_price',
            'auction.latestBid:auction_id,bid',
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

    public function showAllSellers(){
        $sellers = Seller::latest()->get();

        return response()->json($sellers);
    }
}