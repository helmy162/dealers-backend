<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\User;

class UsersController extends Controller
{    
   //constucture where type user 
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->middleware('scope:admin');
    // }

    public function index(Request $request)
    {   

        //get all users with relation to cars
        //get all users with car data and car images
        $users = User::with('cars')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cars' => $user->cars->map(function ($car) {
                    return [
                        'id' => $car->id,
                        'name' => $car->name,
                        'status' => $car->status,
                        'carData' => $car->carData,
                      //use getCarImagesAttribute from CarImage model
                        'carImages' => $car->carImages,

                    ];
                }),
                'bids' => $user->bids->map(function ($bid) {
                    return [
                        'id' => $bid->id,
                        'car_id' => $bid->car_id,
                        'user_id' => $bid->user_id,
                        'bid' => $bid->bid,
                    ];
                }),

            ];
        });
        return response()->json([
            'status' => 'success',
            'UserTable' => 'Admin',
            'data' => $users
        ]);
    }

    public function allUsers(){
        $users = User::all();

        return response()->json($users);
    }


    //add new user inspecter or dealer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required|string',
            'isVerified' => 'required|boolean',
            'status' => 'required|string',
            'phoneNumber' => 'string',
            'bidLimit' => 'integer',
            'assigned_by' => 'integer',
        ]);

        $user                       = new User();
        $user->name                 = $request->name;
        $user->email                = $request->email;
        $user->password             = bcrypt($request->password);
        $user->type                 = $request->role;
        $user->is_verified          = $request->isVerified;
        $user->status               = $request->status;
        $user->phone                = $request->phoneNumber;
        $user->company              = $request->company;
        $user->bid_limit            = $request->bidLimit;
        $user->assigned_by          = $request->assigned_by ?? $user->assigned_by;
        $user->save();


        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$id,
            'role' => 'required|string',
            'isVerified' => 'required|boolean',
            'status' => 'required|string',
            'phoneNumber' => 'string',
            'bidLimit' => 'integer',
            'assigned_by' => 'integer',
        ]);

        $user                       = User::findOrFail($id);
        $user->name                 = $request->name;
        $user->email                = $request->email;
        $user->type                 = $request->role;
        $user->is_verified          = $request->isVerified;
        $user->status               = $request->status;
        $user->phone                = $request->phoneNumber;
        $user->company              = $request->company;
        $user->bid_limit            = $request->bidLimit;
        $user->assigned_by          = $request->assigned_by ?? $user->assigned_by;
        $user->save();

        return response()->json([
            'sucess' => true,
            'user' => $user->makeVisible('assigned_by')
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'UserType' => $user->type,
            'data' => $user->load(
                    'assignedBy',
                    'bids',
                    'bids.car',
                    'bids.car.details',
                    'bids.car.auction',
                    'bids.car.auction.latestBid',
                    'offers',
                    'offers.car',
                    'offers.car.details',
                    'offers.car.highestOffer'
                )
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }


}
