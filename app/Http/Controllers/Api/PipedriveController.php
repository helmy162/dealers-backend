<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPipedriveActivitiesRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PipedriveController extends Controller
{
    private const MAX_LIMIT = 500;

    public function getActivities(GetPipedriveActivitiesRequest $request) : JsonResponse
    {
        try {
            $params = [
                'api_token' => config('pipedrive.token'),
                'type' => config('pipedrive.activity.inspection_appointment'),
                'user_id' => config('pipedrive.user_id'),
                'start' => $request->start,
                'limit' => $request->has('search') ? self::MAX_LIMIT : $request->limit,
                'done' => $request->done,
            ];

            if ($request->has('filter')) {
                $filter = $request->filter;

                if ($filter === 'week') {
                    $startDate = Carbon::now()->startOfWeek()->toDateString();
                    $endDate = Carbon::now()->endOfWeek()->toDateString();
                } elseif ($filter === 'month') {
                    $startDate = Carbon::now()->startOfMonth()->toDateString();
                    $endDate = Carbon::now()->endOfMonth()->toDateString();
                } elseif ($filter === 'year') {
                    $startDate = Carbon::now()->startOfYear()->toDateString();
                    $endDate = Carbon::now()->endOfYear()->toDateString();
                } elseif ($filter === 'today') {
                    $startDate = Carbon::now()->toDateString();
                }

                if (isset($startDate)) {
                    $params['start_date'] = $startDate;
                }

                if (isset($endDate)) {
                    $params['end_date'] = $endDate;
                }
            }

            $response = Http::get(config('pipedrive.url') . '/' . config('pipedrive.version') . '/activities', $params);

            if (! $response->successful()) {
                return response()->json(['error' => 'Failed to fetch activities'], $response->status());
            }

            $activities = $response->collect()->except(['success']);

            if ($request->has('search')) {
                $searchResults = $this->searchResults(collect($activities['data']), $request->search);
                $activities['data'] = $searchResults->values()->toArray();
            }

            return response()->json($activities);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function searchResults(Collection $data, string $search) : Collection
    {
        return $data->filter(function ($item) use ($search) {
            return str_contains(strtolower($item['person_name']), strtolower($search))
                || str_contains(strtolower($item['deal_title']), strtolower($search));
        });
    }
}
