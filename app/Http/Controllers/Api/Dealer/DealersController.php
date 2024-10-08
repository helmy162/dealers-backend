<?php

namespace App\Http\Controllers\Api\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class DealersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::withoutGlobalScopes()->where('status', 'approved')->get()->map(function ($car) {
            return [

                'id' => $car->id,
                'user_name' => $car->user->name ?? 'Test',
                'car_name' => $car->name ?? 'Test',
                //get getGeneralInfoAttribute from Car Model
                //decode general info
                'general_info' => json_decode($car->general_info, true),
                'car_data' => $car->carData,
                'carImages' => $car->carImages,
            ];
        });
        //return all cars
        return response()->json([
            'status' => 'success',
            'UserType' => 'Dealer',
            'cars' => $cars,
        ]);
    }

  //post data for car request when BidNow button clicked
    public function store(Request $request)
    {
        $car = Car::withoutGlobalScopes()->find($request->car_id);
        $car->update([
            'status' => 'requested',
            'dealer_id' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Car Requested Successfully',
        ]);
    }

    //car data for dealer when dealer click on car details
    public function show($id)
    {
        //find car by id when status approved
        $car = Car::where('status', 'approved')->findOrFail($id)
            ->load([
                'details',
                'history',
                'engineTransmission',
                'steering',
                'interior',
                'exterior',
                'specs',
                'wheels',
                'auction:id,car_id,start_at,end_at,start_price',
                'auction.latestBid:auction_id,bid',
            ]);

        return response()->json([
            'car' => $car,
        ]);
    }

  //search for car by name
    public function search(Request $request)
    {
        //filter cars by model or brand or year inside json data
        $cars = Car::withoutGlobalScopes()
            ->where('car_data->model', 'like', '%' . $request->search . '%')
            ->orWhere('car_data->brand', 'like', '%' . $request->search . '%')
            ->orWhere('car_data->year', 'like', '%' . $request->search . '%')
            ->get()->map(function ($car) {
                return [
                    'id' => $car->id,
                    'user_name' => $car->user->name,
                    'car_name' => $car->name,
                    'general_info' => json_decode($car->general_info, true),
                    'carData' => $car->carData,
                    'carImages' => $car->carImages,
                ];
            });

        return response()->json([
            'status' => 'success',
            'cars' => $cars,
        ]);
    }
}
