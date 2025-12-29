<?php

namespace App\Http\Controllers\Manage;

use Hotpop\Devices\Entities\Devices\Device;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{

	public function index()
	{
        // Data for Cards
        $totalUsers = \App\Models\User::count();
        $totalDevices = \App\Entities\Devices\Device::count();
        $newUsersLast7Days = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
        $newUsersToday = \App\Models\User::whereDate('created_at', today())->count();

        // Data for Charts (Last 6 Months User Growth)
        $sixMonthsAgo = now()->subMonths(6);
        $usersPerMonth = \App\Models\User::select('created_at')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m');
            })
            ->map(function ($row) {
                return $row->count();
            });

        // Fill missing months to ensure the chart looks good
        $chartLabels = [];
        $chartData = [];
        $period = \Carbon\CarbonPeriod::create($sixMonthsAgo, '1 month', now());

        foreach ($period as $date) {
            $monthKey = $date->format('Y-m');
            $chartLabels[] = $date->format('M Y');
            $chartData[] = $usersPerMonth[$monthKey] ?? 0;
        }

        // Recent Users
        $recentUsers = \App\Models\User::latest()->take(5)->get();
        // Subscription Plans
        $plans = \App\Entities\SubscriptionPlans\SubscriptionPlan::all();
        // Recent Reports
        $recentReports = \App\Entities\Reports\Report::latest()->take(5)->get();

		$data = [
			'pageTitle' => 'Admin Dashboard',
            'totalUsers' => $totalUsers,
            'totalDevices' => $totalDevices,
            'newUsersLast7Days' => $newUsersLast7Days,
            'newUsersToday' => $newUsersToday,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'recentUsers' => $recentUsers,
            'plans' => $plans,
            'recentReports' => $recentReports
		];

		return view('manage.dashboard.index', $data);
	}
}
