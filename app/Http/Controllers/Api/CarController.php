<?php

namespace App\Http\Controllers\Api;

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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){ 

        $cars = Car::join('auctions', 'auctions.car_id', '=', 'cars.id')
            ->select('cars.*')
            ->where('auctions.end_at','>', Carbon::now())
            ->orderBy('auctions.end_at')
            ->with([
                'details',
                'history',
                'engineTransmission',
                'steering',
                'interior',
                'exterior',
                'specs',
                'wheels',
                'seller',
                'auction',
                'auction.latestBid',
                'auction.latestBid.dealer:id,name'
            ])->orderBy('auctions.end_at')->paginate(5);
        
        return response()->json($cars);
    }

    public function getAllCars(){

        $cars = Car::with([
                'details',
                'history',
                'engineTransmission',
                'steering',
                'interior',
                'exterior',
                'specs',
                'wheels',
                'seller',
                'auction',
                'auction.latestBid',
                'auction.latestBid.dealer:id,name'
            ])->get();

        return response()->json($cars);
    }

    public function createCar(Request $request){

        $validated = $request->validate([
            'seller_id' => 'required|integer',
        ]);

        $requestData = $request->all();
        foreach($requestData as $key => $value){
            if( $value === "true"){
                $requestData[$key] = true ;
            }elseif( $value === "false"){
                $requestData[$key] = false ;
            }
        }

        $car = new Car();
        $car->inspector_id = auth()->user()->id;
        $car->seller_id = $request->seller_id;
        $car->save();

        $details = new Details();
        $details->fill($requestData);
        $details->engine = json_decode($engine);
        $details->car_id = $car->id;
        $details->save();

        $history = new History();
        $history->fill($requestData);
        $history->ownership = $requestData['first_owner'];
        $history->car_id = $car->id;
        $history->save();

        $engine = new Engine();
        $engine->fill($requestData);
        $engine->car_id = $car->id;
        $engine->save();

        $interior = new Interior();
        $interior->fill($requestData);
        $interior->car_id = $car->id;
        $interior->save();

        $specs = new Specs();
        $specs->fill($requestData);
        $specs->car_id = $car->id;
        $specs->save();

        $steering = new Steering();
        $steering->fill($requestData);
        $steering->car_id = $car->id;
        $steering->save();

        $wheels = new Wheels();
        $wheels->fill($requestData);
        $wheels->car_id = $car->id;
        $wheels->save();
        
        $defects = $request->markers;
        $request->hasFile('exterior_images') ? $images = $request->file('exterior_images') : '';
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
    
                    $defect->photo = $fileNameToStore;
                }else{
                    $defect->photo = null;
                }
                array_push($markers, $defect);
            }
        }
        $exterior = new Exterior();
        $exterior->markers = $markers;
        $exterior->car_id = $car->id;
        $exterior->save();

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
        }

        if ($request->hasFile('id_images')) {

            $images = $request->file('id_images');
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
                $path = $image->storeAs('public/id_images',$fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->id_images = $imagesNameArr;
        }

        if ($request->hasFile('registration_card_images')) {

            $images = $request->file('registration_card_images');
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
                $path = $image->storeAs('public/registration_card_images',$fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->registration_card_images = $imagesNameArr;
        }

        if ($request->hasFile('vin_images')) {

            $images = $request->file('vin_images');
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
                $path = $image->storeAs('public/vin_images',$fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->vin_images = $imagesNameArr;
        }

        if ($request->hasFile('insurance_images')) {

            $images = $request->file('insurance_images');
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
                $path = $image->storeAs('public/insurance_images',$fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->insurance_images = $imagesNameArr;
        }
        
        $car->details_id = $details->id;
        $car->history_id = $history->id;
        $car->engine_id = $engine->id;
        $car->interior_id = $interior->id;
        $car->specs_id = $specs->id;
        $car->steering_id = $steering->id;
        $car->wheels_id = $wheels->id;
        $car->exterior_id = $exterior->id;
        $car->save();

        return response()->json([
            'success' => true,
            'car' => $car->load('details',
            'history',
            'engineTransmission',
            'steering',
            'interior',
            'exterior',
            'specs',
            'wheels')
        ], 201);
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

    public function allCarDetails(Request $request, $id){
        $car = Car::with([
            'details',
            'history',
            'engineTransmission',
            'steering',
            'interior',
            'exterior',
            'specs',
            'wheels',
            'seller',
            'auction',
            'auction.bids',
            'auction.bids.dealer'
        ])->findOrFail($id)->makeVisible(['id_images','vin_images','insurance_images','registration_card_images']);

        return response()->json($car);
    }

}