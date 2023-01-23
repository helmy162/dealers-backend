<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\Admin\{AdminCarsReqeustConctoller,UsersController};
//use CarImageController for store car images
use App\Http\Controllers\v1\Inspector\CarImageController;
//use dealer controller for store dealer
use App\Http\Controllers\v1\Dealer\{
    BidController,
    DealersController,
};



use App\Http\Controllers\v1\Inspector\{
    CarController,
    EngineTransmissionController,
    InteriorElecticalsAirConditionerController,
    SteeringSuspensionBrakesController,
    CarSpaceController,
    WheelController,
    GeneralInfoController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//add group with prefix api with v1 version
Route::group(['prefix' => 'v1'], function () {

    // user register
    Route::post('/register', [AuthController::class, 'register']);
    //login user
    Route::post('/login', [AuthController::class , 'login']);
    //reset password
    Route::post('/reset-password', [AuthController::class , 'resetPassword']);

    //new password
    Route::post('/new-password', [AuthController::class , 'newPassword']);

    //route group for auth user
    Route::group(['middleware' => ['auth:sanctum']], function () {

        //route group for admin 
        Route::group(['prefix' => 'admin'], function () {
            //route get all users
            Route::apiResource('/users', UsersController::class);
            Route::get('all-users', [UsersController::class, 'allUsers']);


            // cars routes
            Route::post('edit/car/general-info', [CarController::class, 'editGeneralInfo']);
            Route::post('edit/car/specs', [CarController::class, 'editSpecs']);
            Route::post('edit/car/engine-transmission', [CarController::class, 'editEngineAndTransmission']);
            Route::post('edit/car/interior-electricals-AC', [CarController::class, 'editInteriorElectricalsAndAC']);
            Route::post('edit/car/steering-suspension-brakes', [CarController::class, 'editSteeringSuspensionAndBrakes']);
            Route::post('edit/car/wheels', [CarController::class, 'editWheels']);
            // Route::post('edit/car/images', [CarController::class, 'editImages']);
            // Route::post('edit/car/exterior-condition', [CarController::class, 'editExteriorCondition']);
            Route::delete('cars/{car}',  [CarController::class, 'destroy']);
        });
        
        //route group for inspector
        Route::group(['prefix' => 'inspector'], function () {
            
            Route::post('add/car/general-info', [CarController::class, 'createCar']);
            Route::post('add/car/specs', [CarController::class, 'addSpecs']);
            Route::post('add/car/engine-transmission', [CarController::class, 'addEngineAndTransmission']);
            Route::post('add/car/interior-electricals-AC', [CarController::class, 'addInteriorElectricalsAndAC']);
            Route::post('add/car/steering-suspension-brakes', [CarController::class, 'addSteeringSuspensionAndBrakes']);
            Route::post('add/car/wheels', [CarController::class, 'addWheels']);
            Route::post('add/car/images', [CarController::class, 'addImages']);
            Route::post('add/car/exterior-condition', [CarController::class, 'addExteriorCondition']);
            
        });

        //route group for dealer
        // Route::group(['prefix' => 'dealer'], function () {
        //     //add resourceApi route
        //     Route::apiResource('cars', DealersController::class);

        //     //search data cars by name and model and year and price
        //     Route::get('/cars/search', [DealersController::class, 'search']);

        //     //post data for car request when BidNow button clicked
        //     Route::post('/cars/request', [BidController::class, 'requestCar']);
        // });

        
        //add logout route
        Route::post('/logout', [AuthController::class , 'logout']);
    });
    
    // get cars
    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/all', [CarController::class, 'getAllCars']);

    Route::group(['prefix' => 'dealer'], function () {
        //add resourceApi route
        Route::apiResource('cars', DealersController::class);

        //search data cars by name and model and year and price
        Route::get('/cars/search', [DealersController::class, 'search']);

        //post data for car request when BidNow button clicked
        Route::post('/cars/request', [BidController::class, 'requestCar']);
    });

});