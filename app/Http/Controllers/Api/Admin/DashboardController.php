<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Car;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInspectedCars = Car::whereNotNull([
            'details_id',
            'history_id',
            'engine_id',
            'steering_id',
            'interior_id',
            'specs_id',
            'wheels_id',
            'exterior_id',
            'seller_id',
        ])->count();

        // get inspection stats for everyday of last 7 days
        $startOfWeek = Carbon::now()->subDays(7)->startOfDay();
        $endOfWeek = Carbon::now()->endOfDay();
        $carCountsPerDay = Car::whereNotNull([
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
            ->whereDate('created_at', '>=', $startOfWeek)
            ->whereDate('created_at', '<=', $endOfWeek)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $carCountsPerDay = $carCountsPerDay->pluck('count', 'date')->toArray();

        // Fill in days with count 0 if there are no inspections
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($startOfWeek, $interval, $endOfWeek);
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            if (! isset($carCountsPerDay[$formattedDate])) {
                $carCountsPerDay[$formattedDate] = 0;
            }
        }

        // get top bidding users
        $topBiddingUsers = Bid::selectRaw('user_id, COUNT(*) as bid_count')
            ->groupBy('user_id')
            ->orderByDesc('bid_count')
            ->join('users', 'users.id', '=', 'bids.user_id')
            ->where('users.type', 'dealer')
            ->where('users.status', 'active')
            ->with('dealer')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                $user->name = $user->dealer->name;
                $user->email = $user->dealer->email;
                unset($user->dealer);

                return $user;
            });

        return response()->json([
            'inspection_stats' => [
                'total_inspected_cars' => $totalInspectedCars,
                'this_week' => array_values(array_slice($carCountsPerDay, 1)), // arranged from first day of the week until today
                'last_week_last_day' => array_values(array_slice($carCountsPerDay, 0, 1))[0],
            ],
            'top_bidders' => $topBiddingUsers,
        ]);
    }
}
