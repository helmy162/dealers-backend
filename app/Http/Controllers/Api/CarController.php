<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Mail\newAuction;
use App\Models\Auction;
use App\Models\Car;
use App\Models\Details;
use App\Models\Engine;
use App\Models\Exterior;
use App\Models\History;
use App\Models\Interior;
use App\Models\Specs;
use App\Models\Steering;
use App\Models\User;
use App\Models\Wheels;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mail;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ActiveCars = Car::join('auctions', 'auctions.car_id', '=', 'cars.id')
            ->select('cars.*')
            ->where('auctions.end_at', '>', Carbon::now())
            ->where(function ($query) {
                $query->where('auctions.round', 1)
                    ->orWhere('auctions.round', '<>', 1)
                    ->whereExists(function ($query) {
                        $query->select(\DB::raw(1))
                              ->from('bids')
                              ->whereColumn('bids.auction_id', 'auctions.id')
                              ->where('bids.user_id', auth()->user()->id);
                    });
            })
            ->whereNotNull([
                'details_id',
                'history_id',
                'engine_id',
                'steering_id',
                'interior_id',
                'specs_id',
                'wheels_id',
                'exterior_id',
                'seller_id',
            ])
            ->with([
                'details:car_id,make,model,trim,year,mileage,exterior_color,engine_size,specification,registered_emirates',
                'auction:id,car_id,start_at,end_at,start_price',
                'auction.latestBid:auction_id,bid',
            ])->orderBy('auctions.end_at')->get();

        // expired cars in the last 24h
        $expiredCars = Car::join('auctions', 'auctions.car_id', '=', 'cars.id')
            ->select('cars.*')
            ->where('auctions.end_at', '>', Carbon::now()->subDays(3))
            ->where('auctions.end_at', '<', Carbon::now()->subDay())
            ->whereNotNull([
                'details_id',
                'history_id',
                'engine_id',
                'steering_id',
                'interior_id',
                'specs_id',
                'wheels_id',
                'exterior_id',
                'seller_id',
            ])
            ->orderBy('auctions.end_at')
            ->with([
                'details:car_id,make,model,trim,year,mileage,exterior_color,engine_size,specification,registered_emirates',
                'auction:id,car_id,end_at,start_price',
                'auction.latestBid:auction_id,bid',
            ])->orderByDesc('auctions.end_at')->get();

        if ($request->source == 'dealer_app') {
            return response()->json($ActiveCars->merge($expiredCars));
        } else {
            return response()->json($ActiveCars->merge($expiredCars)->paginate(5));
        }
    }

    public function carsWithExpiredAuctions(Request $request)
    {
        $cars = Car::join('auctions', 'auctions.car_id', '=', 'cars.id')
            ->select('cars.*')
            ->where('auctions.end_at', '<', Carbon::now())
            ->whereNotNull([
                'details_id',
                'history_id',
                'engine_id',
                'steering_id',
                'interior_id',
                'specs_id',
                'wheels_id',
                'exterior_id',
                'seller_id',
            ])
            ->with([
                'details:car_id,make,model,trim,year,mileage,exterior_color,engine_size,specification,registered_emirates',
                'auction:id,car_id,end_at,start_price',
            ])->orderByDesc('auctions.end_at')->get();

        if ($request->source == 'dealer_app') {
            return response()->json($cars);
        } else {
            return response()->json($cars->paginate(5));
        }
    }

    public function createCar(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|integer',
        ]);

        $requestData = $request->all();
        foreach ($requestData as $key => $value) {
            if ($value === 'true') {
                $requestData[$key] = true;
            } elseif ($value === 'false') {
                $requestData[$key] = false;
            }
        }

        $car = new Car();
        $car->inspector_id = auth()->user()->id;
        $car->seller_id = $request->seller_id;
        $car->save();

        $details = new Details();
        $details->fill($requestData);
        $details->car_id = $car->id;
        $details->save();

        $history = new History();
        $history->fill($requestData);
        $history->car_id = $car->id;
        $history->save();

        $engine = new Engine();
        $engine->fill($requestData);
        $engine->car_id = $car->id;
        $engine->save();

        $interior = new Interior();
        $interior->fill($requestData);
        $interior->car_id = $car->id;
        $interior->save();

        $specs = new Specs();
        $specs->fill($requestData);
        $specs->car_id = $car->id;
        $specs->save();

        $steering = new Steering();
        $steering->fill($requestData);
        $steering->car_id = $car->id;
        $steering->save();

        $wheels = new Wheels();
        $wheels->fill($requestData);
        $wheels->car_id = $car->id;
        $wheels->save();

        $exterior = new Exterior();
        $exterior->fill($requestData);
        $exterior->markers = json_decode($request->defects);
        $exterior->car_id = $car->id;
        $exterior->save();

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagesNameArr = [];
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt = $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ', '', pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                //upload image
                $path = $image->storeAs('public/car_images', $fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->images = $imagesNameArr;
        }

        if ($request->hasFile('id_images')) {
            $images = $request->file('id_images');
            $imagesNameArr = [];
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt = $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ', '', pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                //upload image
                $path = $image->storeAs('public/id_images', $fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->id_images = $imagesNameArr;
        }

        if ($request->hasFile('registration_card_images')) {
            $images = $request->file('registration_card_images');
            $imagesNameArr = [];
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt = $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ', '', pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                //upload image
                $path = $image->storeAs('public/registration_card_images', $fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->registration_card_images = $imagesNameArr;
        }

        if ($request->hasFile('vin_images')) {
            $images = $request->file('vin_images');
            $imagesNameArr = [];
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt = $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ', '', pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                //upload image
                $path = $image->storeAs('public/vin_images', $fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->vin_images = $imagesNameArr;
        }

        if ($request->hasFile('insurance_images')) {
            $images = $request->file('insurance_images');
            $imagesNameArr = [];
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt = $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ', '', pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                //upload image
                $path = $image->storeAs('public/insurance_images', $fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->insurance_images = $imagesNameArr;
        }

        $car->details_id = $details->id;
        $car->history_id = $history->id;
        $car->engine_id = $engine->id;
        $car->interior_id = $interior->id;
        $car->specs_id = $specs->id;
        $car->steering_id = $steering->id;
        $car->wheels_id = $wheels->id;
        $car->exterior_id = $exterior->id;
        $car->status = 'approved';
        $car->save();

        $auction = new Auction();
        $auction->car_id = $car->id;
        $auction->start_price = 0;
        $auction->start_at = Carbon::now();
        $auction->end_at = Carbon::now()->addMinutes(20);
        $auction->save();

        $data = [
            'make' => $car->details->make,
            'model' => $car->details->model,
            'year' => $car->details->year,
            'mileage' => $car->details->mileage,
            'start_price' => $auction->start_price,
            'start_at' => $auction->start_at,
            'end_at' => $auction->end_at,
            'image' => asset('/storage/car_images/' . $car->images[0]),
        ];
        // Sending data to WhatsApp webhook
        $response = Http::post('https://hook.us1.make.com/gwrof533jp0974w43brsjpo6kj3qvekl', $data);

        $dealers = User::whereStatus('active')->whereType('dealer')->whereNotifyNewAuction(true)->get();
        foreach ($dealers as $dealer) {
            Mail::to($dealer->email)->queue(new newAuction($car, $dealer));
        }

        // send a push notification to subscriped dealers
        $isPushNotificationSent = NotificationController::sendNewAuctionNotification($car); // returns bool

        return response()->json([
            'success' => true,
            'car' => $car->load(
                'details',
                'history',
                'engineTransmission',
                'steering',
                'interior',
                'exterior',
                'specs',
                'wheels',
                'auction',
                'auction.latestBid'
            ),
        ], 201);
    }

    public function editCar(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $car->fill($request->all());

        if ($request->deletedImages && is_array($request->deletedImages) && count($request->deletedImages)) {
            $car_images = collect($car->images);
            foreach ($request->deletedImages as $del) {
                $car_images = $car_images->reject(fn ($img) => $img == $del);
            }
            $car->images = $car_images->values()->all();
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagesNameArr = $car->images;
            foreach ($images as $image) {
                //get file name with the extension
                $fileNameWithExt = $image->getClientOriginalName();

                //get just filename
                $fileName = str_replace(' ', '', pathinfo($fileNameWithExt, PATHINFO_FILENAME));

                //get just the extension
                $extension = $image->getClientOriginalExtension();

                //file name to store(unique)
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                //upload image
                $path = $image->storeAs('public/car_images', $fileNameToStore);

                //add car images array
                array_push($imagesNameArr, $fileNameToStore);
            }

            $car->images = $imagesNameArr;
        }
        $car->save();

        $requestData = $request->all();
        foreach ($requestData as $key => $value) {
            if ($value === 'true') {
                $requestData[$key] = true;
            } elseif ($value === 'false') {
                $requestData[$key] = false;
            }
        }

        $car->details->fill($requestData);
        $car->details->save();

        $car->history->fill($requestData);
        $car->history->save();

        $car->engineTransmission->fill($requestData);
        $car->engineTransmission->save();

        $car->steering->fill($requestData);
        $car->steering->save();

        $car->interior->fill($requestData);
        $car->interior->save();

        $car->exterior->fill($requestData);
        $car->exterior->save();

        $car->specs->fill($requestData);
        $car->specs->save();

        $car->wheels->fill($requestData);
        $car->wheels->save();

        $car->exterior->fill($requestData);
        $car->exterior->markers = json_decode($request->defects);
        $car->exterior->save();

        $car = Car::with([
            'details',
            'history',
            'engineTransmission',
            'steering',
            'interior',
            'exterior',
            'specs',
            'wheels',
            'seller',
            'auction',
            'auction.bids',
            'auction.bids.dealer',
        ])->findOrFail($id)->makeVisible(['id_images', 'vin_images', 'insurance_images', 'registration_card_images']);

        return response()->json([
            'success' => true,
            'message' => 'Car edited Successfully!',
            'car' => $car,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Car deleted Successfully!',
        ]);
    }

    public function showCar($id)
    {
        //find car by id when status approved
        $car = Car::findOrFail($id)
            ->load([
                'details',
                'history',
                'engineTransmission',
                'steering',
                'interior',
                'exterior',
                'specs',
                'wheels',
                'auction:id,car_id,start_at,end_at,start_price',
                'auction.latestBid:auction_id,bid',
            ]);

        // return my last bid & offer for each car (in case of authenticated route)
        $myBid = null;
        $myOffer = null;

        if (auth()->check()) {
            $myBid = auth()->user()->bids->where('car_id', $id)->last();
            $myOffer = auth()->user()->offers->where('car_id', $id)->last();
        }

        return response()->json([
            'car' => $car,
            'my_bid' => $myBid,
            'my_offer' => $myOffer,
        ]);
    }

    public function allCarDetails(Request $request, $id)
    {
        $car = Car::with([
            'details',
            'history',
            'engineTransmission',
            'steering',
            'interior',
            'exterior',
            'specs',
            'wheels',
            'seller',
            'auction',
            'auction.bids',
            'auction.bids.dealer',
            'offers',
            'offers.dealer',
            'highestOffer',
            'highestOffer.dealer',
            'inspector',
        ])->findOrFail($id)->makeVisible(['id_images', 'vin_images', 'insurance_images', 'registration_card_images']);

        return response()->json([
            'car' => $car,
        ]);
    }
}
