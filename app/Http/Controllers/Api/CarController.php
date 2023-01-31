<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\Details;
use App\Models\History;

use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){  
       
        // $cars = Car::whereNotNUll('engine_id')
        //             ->whereNotNUll('steering_id')
        //             ->whereNotNUll('interior_id')
        //             ->whereNotNUll('exterior_id')
        //             ->whereNotNUll('specs_id')
        //             ->whereNotNUll('wheels_id')
        //             ->with('engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels')
        //             ->paginate(5);

        $cars = Car::with('details', 'history', 'engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels', 'auction', 'seller', 'bids', 'bids.dealer')->paginate(5);
        
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

        $cars = Car::with('details', 'history', 'engineTransmission', 'steering', 'interior', 'exterior', 'specs', 'wheels', 'auction', 'seller', 'bids', 'bids.dealer')->get();

        return response()->json($cars);
    }

    public function createCar(Request $request){

        $car = new Car();
        $car->inspector_id = auth()->user()->id;
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

    public function addImages(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->images ? abort(403, 'Forbidden') : '';
        
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

    public function editGeneralInfo(Request $request){

        $car = Car::findOrFail($request->car_id);

        if(!$car->details_id){
            $details = new Details();
            $details->fill($request->all());
            $details->car_id = $car->id;
            $details->save();

            $car->details_id = $details->id;
        }else{
            $car->details->fill($request->all());
            $car->details->save();
        }

        if(!$car->history_id){
            $history = new History();
            $history->fill($request->all());
            $history->ownership = $request->first_owner;
            $history->car_id = $car->id;
            $history->save();

            $car->history_id = $history->id;
        }else{
            $car->history->fill($request->all());
            $car->history->save();
        }
        
        $car->save();
        $car->history;
        $car->details;

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
            "message" => 'Car deleted Successfully!'
        ]);
    }

}