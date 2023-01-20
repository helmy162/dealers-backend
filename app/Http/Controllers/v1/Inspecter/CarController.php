<?php

namespace App\Http\Controllers\v1\Inspecter;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\Details;
use App\Models\History;
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

        $cars = Car::with('details', 'history', 'engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels')->paginate(5);
        
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

        $cars = Car::with('details', 'history', 'engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels')->get();

        return response()->json($cars);
    }

    public function createCar(Request $request){

        $car = new Car();
        $car->inspector_id = 1;
        $car->save();

        $details = new Details();
        $details->fill($request->all());
        $details->car_id = $car->id;
        $details->save();

        $history = new History();
        $history->fill($request->all());
        $history->ownership = $request->first_owner;
        $history->car_id = $car->id;
        $history->save();

        $car->details_id = $details->id;
        $car->history_id = $history->id;
        $car->save();
        $car->history;
        $car->details;

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
        $car->specs;

        return response()->json([
            'success' => true,
            'car' => $car
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
        $car->engineTransmission;

        return response()->json([
            'success' => true,
            'car' => $car
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
        $car->interior;

        return response()->json([
            'success' => true,
            'car' => $car
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
        $car->steering;

        return response()->json([
            'success' => true,
            'car' => $car
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
        $car->wheels;

        return response()->json([
            'success' => true,
            'car' => $car
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
        $defects = $request->markers;

        $request->hasFile('images') ? $images = $request->file('images') : '';

        $markers = [];
        if($defects && is_array($defects)){
            foreach ($defects as $defect) {
                $defect = json_decode($defect);
                if(isset($images) &&  is_array($images) && is_int($defect->photo)){
                    //get image from images array
                    $image = $images[$defect->photo];
    
                    //get file name with the extension
                    $fileNameWithExt= $image->getClientOriginalName();
    
                    //get just filename
                    $fileName = str_replace(' ','',pathinfo($fileNameWithExt, PATHINFO_FILENAME));
    
                    //get just the extension
                    $extension = $image->getClientOriginalExtension();
    
                    //file name to store(unique)
                    $fileNameToStore = $fileName.'_'.time().'.'.$extension;
    
                    //upload image
                    $path = $image->storeAs('public/defect_images',$fileNameToStore);
    
                    $defect->photo = '/storage/defect_images/'.$fileNameToStore;
                }else{
                    $defect->photo = null;
                }
                array_push($markers, $defect);
            }
        }

        $exterior->markers = $markers;
        $exterior->car_id = $car->id;
        $exterior->save();
        
        $car->exterior_id = $exterior->id;
        $car->save();
        $car->exterior;

        return response()->json([
            'success' => true,
            'car' => $car
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return response()->json([
            'success' => 'true',
        ]);
    }

}
