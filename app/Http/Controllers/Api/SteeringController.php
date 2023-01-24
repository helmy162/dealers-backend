<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Steering;

class SteeringController extends Controller
{
    public function addSteeringSuspensionAndBrakes(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->steering_id ? abort(403, 'Forbidden') : '';

        $steering = new Steering();
        $steering->fill($request->all());
        $steering->car_id = $car->id;
        $steering->save();
        
        $car->steering_id = $steering->id;
        $car->save();
        $car->steering;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function editSteeringSuspensionAndBrakes(Request $request){
        $car = Car::findOrFail($request->car_id);

        if(!$car->steering_id){
            $steering = new Steering();
            $steering->fill($request->all());
            $steering->car_id = $car->id;
            $steering->save();

            $car->steering_id = $steering->id;
            $car->save();
        }else{
            $car->steering->fill($request->all());
            $car->steering->save();
        }
        
        $car->steering;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }
}
