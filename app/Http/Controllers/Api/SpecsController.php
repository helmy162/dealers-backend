<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Specs;
use App\Models\Car;

class SpecsController extends Controller
{
    public function addSpecs(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->specs_id ? abort(403, 'Forbidden') : '';

        $specs = new Specs();
        $specs->fill($request->all());
        $specs->car_id = $car->id;
        $specs->save();
        
        $car->specs_id = $specs->id;
        $car->save();
        $car->specs;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function editSpecs(Request $request){
        $car = Car::findOrFail($request->car_id);

        if(!$car->specs_id){
            $specs = new Specs();
            $specs->fill($request->all());
            $specs->car_id = $car->id;
            $specs->save();

            $car->specs_id = $specs->id;
            $car->save();
        }else{
            $car->specs->fill($request->all());
            $car->specs->save();
        }
        
        $car->specs;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }
}
