<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class AdminCarsReqeustConctoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all cars for admin   withoutGlobalScopes where status = approved
        $cars = Car::withoutGlobalScopes()->get()->map(function ($car) {
            return [
                'id' => $car->id,
                'name' => $car->name,
                'status' => $car->status,
                'carData' => $car->carData,
                'carImages' => $car->carImages,
            ];
        });
        //return all cars
        return response()->json([
            'status' => 'success',
            'cars' => $cars,
        ]);
    }

    //search for data in cars table
    public function search(Request $request)
    {
        //get search data
        $search = $request->search;
        //get all cars for admin   withoutGlobalScopes where status = approved
        $cars = Car::withoutGlobalScopes()->where('name', 'like', '%' . $search . '%')->get()->map(function ($car) {
            return [
                'id' => $car->id,
                'name' => $car->name,
                'status' => $car->status,
                'carData' => $car->carData,
                'carImages' => $car->carImages,
            ];
        });
        //return all cars
        return response()->json([
            'status' => 'success',
            'cars' => $cars,
        ]);
    }

    //change car status to approved
    public function approveCar($id)
    {
        //get car by id
        $car = Car::withoutGlobalScopes()->find($id);
        //change car status to approved
        $car->status = 'approved';
        //save car
        $car->save();
        //return success message
        return response()->json([
            'status' => 'success',
            'message' => 'Car approved successfully',
        ]);
    }

    //change car status to rejected
    public function rejectCar($id)
    {
        //get car by id
        $car = Car::withoutGlobalScopes()->find($id);
        //change car status to rejected
        $car->status = 'rejected';
        //save car
        $car->save();
        //return success message
        return response()->json([
            'status' => 'success',
            'message' => 'Car rejected successfully',
        ]);
    }

    //change car status to pending
    public function pendingCar($id)
    {
        //get car by id
        $car = Car::withoutGlobalScopes()->find($id);
        //change car status to pending
        $car->status = 'pending';
        //save car
        $car->save();
        //return success message
        return response()->json([
            'status' => 'success',
            'message' => 'Car pending successfully',
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
            'seller_id',
        ])->with([
            'details:id,car_id,make,model,year',
            'seller:id,name',
            'auction:car_id,start_at,end_at',
            'inspector:id,name',
        ])->get();

        return response()->json($cars);
    }
}
