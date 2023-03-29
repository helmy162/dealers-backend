<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seller;

class SellerController extends Controller
{   
    public function index(){
        return response()->json(['data' => Seller::all()]);
    }

    public function show($id){
        $seller = Seller::findOrFail($id);
        return response()->json(['data' => $seller]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:sellers',
            'phone' => 'required'
        ]);

        $seller = new Seller();
        $seller->fill($validated);
        $seller->save();

        return response()->json([
            "success" => true,
            "seller" => $seller
        ]);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:sellers,email,'.$id,
            'phone' => 'required',
        ]);

        $seller = Seller::findOrFail($id);
        $seller->fill($validated);
        $seller->save();

        return response()->json([
            "success" => true,
            "seller" => $seller
        ]);

    }

    public function destroy($id){
        $seller = Seller::findOrFail($id);
        $seller->delete();

        return response()->json([
            "success" => true,
            "message" => 'Seller deleted Successfully!'
        ]);
    }

    public function webhook(Request $request){
        $seller             = new Seller();
        $seller->name       = $request->current['name'];
        $seller->email      = $request->current['email'][0]['value'];
        $seller->phone      = $request->current['phone'][0]['value'];
        $seller->save();
    }
}
