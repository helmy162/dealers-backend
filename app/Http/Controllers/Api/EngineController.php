<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Engine;

class EngineController extends Controller
{
    public function addEngineAndTransmission(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->engine_id ? abort(403, 'Forbidden') : '';

        $engine = new Engine();
        $engine->fill($request->all());
        $engine->car_id = $car->id;
        $engine->save();
        
        $car->engine_id = $engine->id;
        $car->save();
        $car->engineTransmission;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function editEngineAndTransmission(Request $request){
        $car = Car::findOrFail($request->car_id);

        if(!$car->engine_id){
            $engine = new Engine();
            $engine->fill($request->all());
            $engine->car_id = $car->id;
            $engine->save();

            $car->engine_id = $engine->id;
            $car->save();
        }else{
            $car->engineTransmission->fill($request->all());
            $car->engineTransmission->save();
        }

        $car->engineTransmission;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }
}
