<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPipedriveActivitiesRequest;
use Illuminate\Support\Collection;
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

        $activities = $response->collect()->except(['success']);

        if ($request->has('search')) {
            $searchResults = $this->searchResults(collect($activities['data']), $request->search);
            $activities['data'] = $searchResults->values()->toArray();
        }

        return response()->json($activities);
    }

    private function searchResults(Collection $data, string $search)
    {
        return $data->filter(function ($item) use ($search) {
            return str_contains(strtolower($item['person_name']), strtolower($search));
        });
    }
}
