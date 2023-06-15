<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\StorePipedrivePersonRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Seller;

class SellerController extends Controller
{
    public function index()
    {
        return response()->json(Seller::latest()->get());
    }

    public function show($id)
    {
        $seller = Seller::findOrFail($id);
        return response()->json(['seller' => $seller]);
    }

    public function store(Request $request)
    {
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

    public function update(Request $request, $id)
    {
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

    public function destroy($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->delete();

        return response()->json([
            "success" => true,
            "message" => 'Seller deleted Successfully!'
        ]);
    }

    /**
     * Pipedrive webhook for creating Sellers from Pipedrive Persons
     * @see https://carsxchange.pipedrive.com/settings/webhooks
     * @see https://pipedrive.readme.io/docs/guide-for-webhooks
     */
    public function webhook(StorePipedrivePersonRequest $request)
    {
        try {
            $validated = $request->safe()->only('current');
            $current = $validated['current'];
            $name = $current['name'];
            $email = $current['email'][0]['value'];
            $phone = $current['phone'][0]['value'];

            $seller = Seller::firstOrCreate(
                ['email' => $email, 'name' => $name],
                ['phone' => $phone]
            );

            return response()->json($seller);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
