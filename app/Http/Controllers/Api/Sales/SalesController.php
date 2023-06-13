<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\User;

class SalesController extends Controller
{
    public function showCar(Request $request, $id)
    {
        $car = Car::findOrFail($id)
            ->makeVisible(['id_images', 'vin_images', 'insurance_images', 'registration_card_images'])
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

    public function showAllCars()
    {
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

    public function showAllDealers()
    {
        $users = User::whereType('dealer')->latest()->select('id', 'name', 'email', 'phone', 'bid_limit', 'type')->get();

        return response()->json($users);
    }

    public function showDealer($id)
    {
        $dealer = User::whereType('dealer')->select('id', 'name', 'email', 'phone', 'bid_limit', 'type', 'company', 'assigned_by')->find($id) ?? abort(404, 'Dealer not a Found!');

        return response()->json($dealer->load('assignedBy:id,name'));
    }

    public function showAllSales()
    {
        $users = User::whereType('sales')->latest()->select('id', 'name')->get();

        return response()->json($users);
    }

    public function updateDealer(Request $request, $id)
    {
        $request->validate([
            'assigned_by' => 'required|integer'
        ]);

        $sales = User::whereType('sales')->find($request->assigned_by) ?? abort(404, 'Sales user not a Found!');
        $dealer = User::whereType('dealer')->select('id', 'name', 'email', 'phone', 'bid_limit', 'type')->find($id) ?? abort(404, 'Dealer not a Found!');

        $dealer->assigned_by = $request->assigned_by;
        $dealer->save();

        return response()->json($dealer->load('assignedBy:id,name'));
    }
}
