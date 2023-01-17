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

    public function getAllCars(){

        $cars = Car::all();
        
        return response()->json($cars);
    }

    public function createCarWithGeneralInfo(Request $request){

        $car = new Car();
        $car->inspector_id = 1;
        $car->fill($request->all());
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addSpecs(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->specs_status = true;
        $car->save();
        
        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addEngineAndTransmission(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->engine_status = true;
        $car->save();
        
        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addInteriorElectricalsAndAC(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->interior_status = true;
        $car->save();
        
        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addSteeringSuspensionAndBrakes(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->steering_status = true;
        $car->save();
        
        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addWheels(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->wheels_status = true;
        $car->save();
        
        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addImages(Request $request){
        $car = Car::findOrFail($request->car_id);
        
        // dd($request);
        if ($request->hasFile('images')) {

            $images = $request->file('images');
            $imagesNameArr = array();
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt= $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ','',pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;

                //upload image
                $path = $image->storeAs('public/car_images',$fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }


            $car->images = $imagesNameArr;
            $car->images_status = true;
            $car->save();
        }

        
        return response()->json([
            'success' => 'true',
            'car' => $car
        ]);
    }

    public function addExteriorCondition(Request $request){
        $car = Car::findOrFail($request->car_id);
        $car->fill($request->all());
        $car->exterior_status = true;
        $car->save();
        
        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

}
