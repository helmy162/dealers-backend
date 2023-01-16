<?php

namespace App\Http\Controllers\v1\Inspecter;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;

use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
       
        $cars = Car::paginate(5);
        
        return response()->json($cars);
    }

    public function createCarWithGeneralInfo(Request $request){

        $car = new Car();
        $car->inspector_id = 1;
        $car->fill($request->all());
        $car->save();

        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addSpecs(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addEngineAndTransmission(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addInteriorElectricalsAndAC(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addSteeringSuspensionAndBrakes(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addWheels(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addImages(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function addExteriorCondition(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->save();
        
        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    //build storeImages function
    public function storeImages(Request $request)
    {
        //add car images to uploads folder and store the path in database
        $carImages = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('uploads', 'public');
            $carImages[] = ['path' => $path];
        }
        //create car images and car_id is the id of the car
        $car = Car::find($request->car_id);
        $car->carImages()->createMany($carImages);
        //return the car images
        return response()->json([
            'carImages' => $carImages
        ], 201);
    }
}
