<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Wheels;

class WheelsController extends Controller
{
    public function addWheels(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->wheels_id ? abort(403, 'Forbidden') : '';

        $wheels = new Wheels();
        $wheels->fill($request->all());
        $wheels->car_id = $car->id;
        $wheels->save();
        
        $car->wheels_id = $wheels->id;
        $car->save();
        $car->wheels;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function editWheels(Request $request){
        $car = Car::findOrFail($request->car_id);

        if(!$car->wheels_id){
            $wheels = new Wheels();
            $wheels->fill($request->all());
            $wheels->car_id = $car->id;
            $wheels->save();

            $car->wheels_id = $wheels->id;
            $car->save();
        }else{
            $car->wheels->fill($request->all());
            $car->wheels->save();
        }  
        
        $car->wheels;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }
}
