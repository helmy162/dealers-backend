<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Exterior;

class ExteriorController extends Controller
{
    public function addExteriorCondition(Request $request){
        $car = Car::findOrFail($request->car_id);

        $car->exterior_id ? abort(403, 'Forbidden') : '';
        
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
}
