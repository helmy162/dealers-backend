<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\{AdminCarsReqeustConctoller,UsersController};
//use dealer controller for store dealer
use App\Http\Controllers\Api\Dealer\{
    BidController,
    DealersController,
};



use App\Http\Controllers\Api\{
    CarController,
    EngineController,
    InteriorController,
    SteeringController,
    SpecsController,
    WheelsController,
    DetailsController,
    ExteriorController
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
        // Route::group(['prefix' => 'admin', 'middleware' => ['is_admin']], function () {
        Route::group(['prefix' => 'admin'], function () {
            //route get all users
            Route::apiResource('/users', UsersController::class);
            Route::get('all-users', [UsersController::class, 'allUsers']);

            // cars routes
            Route::post('edit/car/general-info', [CarController::class, 'editGeneralInfo']);
            Route::post('edit/car/specs', [SpecsController::class, 'editSpecs']);
            Route::post('edit/car/engine-transmission', [EngineController::class, 'editEngineAndTransmission']);
            Route::post('edit/car/interior-electricals-AC', [InteriorController::class, 'editInteriorElectricalsAndAC']);
            Route::post('edit/car/steering-suspension-brakes', [SteeringController::class, 'editSteeringSuspensionAndBrakes']);
            Route::post('edit/car/wheels', [WheelsController::class, 'editWheels']);
            // Route::post('edit/car/images', [CarController::class, 'editImages']);
            // Route::post('edit/car/exterior-condition', [ExteriorController::class, 'editExteriorCondition']);
            Route::delete('cars/{car}',  [CarController::class, 'destroy']);
        });
        
        //route group for inspector
        // Route::group(['prefix' => 'inspector', 'middleware' => ['is_inspector']], function () {
        Route::group(['prefix' => 'inspector'], function () {
            
            Route::post('add/car/general-info', [CarController::class, 'createCar']);
            Route::post('add/car/specs', [SpecsController::class, 'addSpecs']);
            Route::post('add/car/engine-transmission', [EngineController::class, 'addEngineAndTransmission']);
            Route::post('add/car/interior-electricals-AC', [InteriorController::class, 'addInteriorElectricalsAndAC']);
            Route::post('add/car/steering-suspension-brakes', [SteeringController::class, 'addSteeringSuspensionAndBrakes']);
            Route::post('add/car/wheels', [WheelsController::class, 'addWheels']);
            Route::post('add/car/images', [CarController::class, 'addImages']);
            Route::post('add/car/exterior-condition', [ExteriorController::class, 'addExteriorCondition']);
            
        });

        //route group for dealer
        Route::group(['prefix' => 'dealer'], function () {
            //add resourceApi route
            Route::apiResource('cars', DealersController::class);

            //search data cars by name and model and year and price
            Route::get('/cars/search', [DealersController::class, 'search']);

            //post data for car request when BidNow button clicked
            Route::post('/cars/request', [BidController::class, 'requestCar']);
        });

        //add logout route
        Route::post('/logout', [AuthController::class , 'logout']);
    });
    
    // get cars
    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/all', [CarController::class, 'getAllCars']);

});