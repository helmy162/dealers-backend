<?php

namespace App\Http\Controllers\Api\Inspector;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Seller;
use Illuminate\Http\Request;

class InspectorsController extends Controller
{
    public function index()
    {
        return response()->json(Seller::latest()->get());
    }

    public function showCar(Request $request, $id)
    {
        $car = Car::findOrFail($id)
            ->makeVisible(['id_images', 'insurance_images', 'registration_card_images'])
            ->load([
                'details:id,car_id,make,model,year',
                'seller:id,name',
                'inspector:id,name',
            ]);

        return response()->json([
            'car' => $car
        ]);
    }

}
