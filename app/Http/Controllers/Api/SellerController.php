<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seller;

class SellerController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:sellers',
            'phone' => 'required'
        ]);

        $seller = new Seller();
        $seller->fill($validated);
        $seller->save();

        return response()->json($seller);
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

        return response()->json($seller);

    }
}
