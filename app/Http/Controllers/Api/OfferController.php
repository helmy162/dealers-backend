<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer',
            'car_id' => 'required|integer',
        ]);

        $offer = new Offer();
        $offer->user_id = auth()->user()->id;
        $offer->car_id = $request->car_id;
        $offer->amount = $request->amount;
        $offer->save();

        return response()->json([
            'success' => true,
            'offer' => $offer,
        ]);
    }
}
