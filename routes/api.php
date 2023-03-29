<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\{AdminCarsReqeustConctoller,UsersController};
use App\Http\Controllers\Api\Dealer\DealersController;

use App\Http\Controllers\Api\{
    CarController,
    EngineController,
    InteriorController,
    SteeringController,
    SpecsController,
    WheelsController,
    DetailsController,
    ExteriorController,
    SellerController,
    AuctionController,
    BidController,
    PusherController,
    UserController
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
    Route::post('/login', [AuthController::class , 'login'])->name('login');
    //reset password
    Route::post('/reset-password', [AuthController::class , 'resetPassword']);

    //route group for auth user
    Route::group(['middleware' => ['auth:sanctum']], function () {

        //route group for admin 
        // Route::group(['prefix' => 'admin', 'middleware' => ['is_admin']], function () {
        Route::group(['prefix' => 'admin'], function () {
            //route get all users
            Route::apiResource('/users', UsersController::class);
            Route::get('all-users', [UsersController::class, 'allUsers']);

            //seller routes
            Route::apiResource('sellers', SellerController::class);
            Route::post('pipedrive/seller', [SellerController::class, 'webhook']);

            //auction routes
            Route::put('auction/declare-winner', [AuctionController::class, 'declareWinner']);
            Route::apiResource('auctions', AuctionController::class);

            // cars routes
            Route::get('car/{car}', [CarController::class, 'allCarDetails']);
            Route::post('car/{car}', [CarController::class, 'editCar']);
            Route::delete('cars/{car}',  [CarController::class, 'destroy']);

        });
        
        //route group for inspector
        // Route::group(['prefix' => 'inspector', 'middleware' => ['is_inspector']], function () {
        Route::group(['prefix' => 'inspector'], function () {
            
            Route::post('car', [CarController::class, 'createCar']);
            
        });

        //route group for dealer
        // Route::group(['prefix' => 'dealer', 'middleware' => ['is_dealer']], function () {
        Route::group(['prefix' => 'dealer'], function () {
            //add resourceApi route
            Route::apiResource('cars', DealersController::class);

            //search data cars by name and model and year and price
            // Route::get('/cars/search', [DealersController::class, 'search']);

            // make a bid
            Route::post('bid', [BidController::class, 'store']);

            // edit profile recieved notifications
            Route::put('profile/notifications' ,[UserController::class, 'updateNotifications']);
        });

        Route::group(['prefix' => 'pusher'], function(){
            // Route::post('auth-user', [PusherController::class, 'authenticateUser']);
            Route::post('auth-channel', [PusherController::class, 'authorizeChannel']);
        });
    
        //add logout route
        Route::post('/logout', [AuthController::class , 'logout']);

            //new password
            Route::post('/new-password', [AuthController::class , 'newPassword']);
    });
    
    // get cars
    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/all', [CarController::class, 'getAllCars']);

    // pipedrive creating sellers webhook
    Route::post('pipedrive/seller', [SellerController::class, 'webhook']);
});