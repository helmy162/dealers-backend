<?php

use App\Http\Controllers\Api\Admin\AdminCarsReqeustConctoller;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\Closer\ClosersController;
use App\Http\Controllers\Api\Dealer\DealersController;
use App\Http\Controllers\Api\Inspector\InspectorsController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\PipedriveController;
use App\Http\Controllers\Api\PusherController;
use App\Http\Controllers\Api\Sales\SalesController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    //reset password
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    //reset password
    Route::post('/confirm-reset-password', [AuthController::class, 'confirmResetPassword']);

    // Authenticated User Routes
    Route::group(['middleware' => ['auth:sanctum']], function () {
        //route group for admin
        Route::group(['prefix' => 'admin', 'middleware' => ['is_admin']], function () {
            //route get dashboard stats
            Route::apiResource('/stats', DashboardController::class);

            //route get all users
            Route::apiResource('/users', UsersController::class);

            //seller routes
            Route::apiResource('sellers', SellerController::class);
            Route::post('pipedrive/seller', [SellerController::class, 'webhook']);

            //auction routes
            Route::put('auction/declare-winner', [AuctionController::class, 'declareWinner']);
            Route::apiResource('auctions', AuctionController::class);

            // cars routes
            Route::get('cars', [AdminCarsReqeustConctoller::class, 'showAllCars']);
            Route::get('cars/{car}', [CarController::class, 'allCarDetails']);
            Route::post('cars/{car}', [CarController::class, 'editCar']);
            Route::delete('cars/{car}', [CarController::class, 'destroy']);
        });

        //route group for inspector
        Route::group(['prefix' => 'inspector', 'middleware' => ['is_inspector']], function () {
            Route::post('car', [CarController::class, 'createCar']);

            Route::get('cars/{car}', [InspectorsController::class, 'showCar']);

            Route::get('sellers', [InspectorsController::class, 'index']);
        });

        //route group for dealer
        Route::group(['prefix' => 'dealer', 'middleware' => ['is_dealer']], function () {
            //search data cars by name and model and year and price
            // Route::get('/cars/search', [DealersController::class, 'search']);

            // make a bid
            Route::post('bid', [BidController::class, 'store']);

            // make an offer
            Route::post('offer', [OfferController::class, 'store']);

            // get a car
            Route::get('cars/{car}', [CarController::class, 'showCar']);
        });

        //route group for closer
        Route::group(['prefix' => 'closer', 'middleware' => ['is_closer']], function () {
            Route::get('cars/{car}', [ClosersController::class, 'showCar']);
            Route::get('cars', [ClosersController::class, 'showAllCars']);

            Route::apiResource('sellers', SellerController::class);
        });

        //route group for sales
        Route::group(['prefix' => 'sales', 'middleware' => ['is_sales']], function () {
            Route::get('cars', [SalesController::class, 'showAllCars']);
            Route::get('cars/{car}', [SalesController::class, 'showCar']);
            Route::get('users', [SalesController::class, 'showAllDealers']);
            Route::get('users/{id}', [SalesController::class, 'showDealer']);
            Route::get('sales', [SalesController::class, 'showAllSales']);

            // update dealers assigned by
            Route::put('dealers/{id}', [SalesController::class, 'updateDealer']);

            Route::post('auctions', [AuctionController::class, 'store']);
            Route::put('auctions/{id}', [AuctionController::class, 'update']);
            Route::delete('auctions/{id}', [AuctionController::class, 'destroy']);
        });

        Route::group(['middleware' => ['is_dealer']], function () {
            // get cars
            Route::get('cars', [CarController::class, 'index']);
            Route::get('cars/expired-auction', [CarController::class, 'carsWithExpiredAuctions']);
        });

        Route::group(['prefix' => 'pusher'], function () {
            // Route::post('auth-user', [PusherController::class, 'authenticateUser']);
            Route::post('auth-channel', [PusherController::class, 'authorizeChannel']);
        });

        //add logout route
        Route::post('/logout', [AuthController::class, 'logout']);
        //new password
        Route::post('/new-password', [AuthController::class, 'newPassword']);
        // edit profile recieved notifications
        Route::put('profile/notifications', [UserController::class, 'updateNotifications']);

        // user data
        Route::get('/profile', [UserController::class, 'index']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::get('/profile/bids', [UserController::class, 'getOwnBids']);
        Route::get('/profile/offers', [UserController::class, 'getOwnOffers']);
        Route::post('/profile/deactivate', [UserController::class, 'deactivateAccount']);
    });

    Route::get('cars/{id}', [CarController::class, 'showCar']);

    Route::group(['prefix' => 'webhook'], function () {
        Route::group(['middleware' => 'pipedrive-http'], function() {
            /**
             * Webhook for creating sellers
             *
             * @see https://pipedrive.readme.io/docs/guide-for-webhooks
             */
            Route::post('pipedrive/seller', [SellerController::class, 'webhook']);
        });
    });

    // Get activities from Pipedrive
    Route::post('pipedrive/activities', [PipedriveController::class, 'getActivities']);
});
