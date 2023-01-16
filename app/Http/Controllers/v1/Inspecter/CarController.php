<?php

namespace App\Http\Controllers\v1\Inspecter;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarImage;
//use request
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

    public function createCarWithGeneralInfo(){
        return response()->json([
            'success' => true,
            'data' => 'createCarWithGeneralInfo'
        ]);
    }

    public function addSpecs(){
        return response()->json([
            'success' => true,
            'data' => 'addSpecs'
        ]);
    }

    public function addEngineAndTransmission(){
        return response()->json([
            'success' => true,
            'data' => 'addEngineAndTransmission'
        ]);
    }

    public function addInteriorElectricalsAndAC(){
        return response()->json([
            'success' => true,
            'data' => 'addInteriorElectricalsAndAC'
        ]);
    }

    public function addSteeringSuspensionAndBrakes(){
        return response()->json([
            'success' => true,
            'data' => 'addSteeringSuspensionAndBrakes'
        ]);
    }

    public function addWheels(){
        return response()->json([
            'success' => true,
            'data' => 'addWheels'
        ]);
    }

    public function addImages(){
        return response()->json([
            'success' => true,
            'data' => 'addImages'
        ]);
    }

    public function addExteriorCondition(){
        return response()->json([
            'success' => true,
            'data' => 'addExteriorCondition'
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
