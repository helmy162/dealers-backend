<?php

namespace App\Http\Controllers\v1\Inspecter;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\Engine;
use App\Models\Exterior;
use App\Models\Interior;
use App\Models\Specs;
use App\Models\Steering;
use App\Models\Wheels;

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
       
        // $cars = Car::whereNotNUll('engine_id')
        //             ->whereNotNUll('steering_id')
        //             ->whereNotNUll('interior_id')
        //             ->whereNotNUll('exterior_id')
        //             ->whereNotNUll('specs_id')
        //             ->whereNotNUll('wheels_id')
        //             ->with('engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels')
        //             ->paginate(5);

        $cars = Car::paginate(5);
        
        return response()->json($cars);
    }

    public function getAllCars(){

        // $cars = Car::whereNotNUll('engine_id')
        //             ->whereNotNUll('steering_id')
        //             ->whereNotNUll('interior_id')
        //             ->whereNotNUll('exterior_id')
        //             ->whereNotNUll('specs_id')
        //             ->whereNotNUll('wheels_id')
        //             ->with('engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels')
        //             ->get();

        $cars = Car::all();
        
        return response()->json($cars);
    }

    public function createCar(Request $request){

        $car = new Car();
        $car->inspector_id = 1;
        $car->fill($request->all());
        $car->ownership = $request->first_owner;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function addSpecs(Request $request){
        $car = Car::findOrFail($request->car_id);

        $specs = new Specs();
        $specs->fill($request->all());
        $specs->car_id = $car->id;
        $specs->save();
        
        $car->specs_id = $specs->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->with('specs')
        ]);
    }

    public function addEngineAndTransmission(Request $request){
        $car = Car::findOrFail($request->car_id);

        $engine = new Engine();
        $engine->fill($request->all());
        $engine->car_id = $car->id;
        $engine->save();
        
        $car->engine_id = $engine->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->with('engineTransmission')->first()
        ]);
    }

    public function addInteriorElectricalsAndAC(Request $request){
        $car = Car::findOrFail($request->car_id);

        $interior = new Interior();
        $interior->fill($request->all());
        $interior->car_id = $car->id;
        $interior->save();
        
        $car->interior_id = $interior->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->with('interior')->first()
        ]);
    }

    public function addSteeringSuspensionAndBrakes(Request $request){
        $car = Car::findOrFail($request->car_id);

        $steering = new Steering();
        $steering->fill($request->all());
        $steering->car_id = $car->id;
        $steering->save();
        
        $car->steering_id = $steering->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->with('specs')->first()
        ]);
    }

    public function addWheels(Request $request){
        $car = Car::findOrFail($request->car_id);

        $wheels = new Wheels();
        $wheels->fill($request->all());
        $wheels->car_id = $car->id;
        $wheels->save();
        
        $car->wheels_id = $wheels->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->with('wheels')->first()
        ]);
    }

    public function addImages(Request $request){
        $car = Car::findOrFail($request->car_id);
        
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
                array_push($imagesNameArr, '/storage/car_images/'.$fileNameToStore);
            }


            $car->images = $imagesNameArr;
            $car->save();
        }

        
        return response()->json([
            'success' => 'true',
            'car' => $car
        ]);
    }

    public function addExteriorCondition(Request $request){
        $car = Car::findOrFail($request->car_id);

        $exterior = new Exterior();
        $exterior->fill($request->all());
        $exterior->car_id = $car->id;
        $exterior->save();
        
        $car->exterior_id = $exterior->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->with('exterior')->first()
        ]);
    }

}
