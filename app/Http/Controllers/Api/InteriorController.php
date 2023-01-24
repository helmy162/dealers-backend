<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Interior;

class InteriorController extends Controller
{
    public function addInteriorElectricalsAndAC(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->interior_id ? abort(403, 'Forbidden') : '';

        $interior = new Interior();
        $interior->fill($request->all());
        $interior->car_id = $car->id;
        $interior->save();
        
        $car->interior_id = $interior->id;
        $car->save();
        $car->interior;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function editInteriorElectricalsAndAC(Request $request){
        $car = Car::findOrFail($request->car_id);

        if(!$car->interior_id){
            $interior = new Interior();
            $interior->fill($request->all());
            $interior->car_id = $car->id;
            $interior->save();

            $car->interior_id = $interior->id;
            $car->save();
        }else{
            $car->interior->fill($request->all());
            $car->interior->save();
        }

        $car->interior;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }
}
