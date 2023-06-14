<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPipedriveActivitiesRequest;
use Illuminate\Support\Facades\Http;

class PipedriveController extends Controller
{
    public function getActivities(GetPipedriveActivitiesRequest $request)
    {
        $response = Http::get(config('pipedrive.url') . '/' . config('pipedrive.version') .  '/activities', [
            'api_token' => config('pipedrive.token'),
            'type' => config('pipedrive.activity.inspection_appointment'),
            'start' => $request->start,
            'limit' => $request->limit,
            'done' => $request->done,
        ]);

        return response()->json($response->json());
    }
}
