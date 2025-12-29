<?php
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Entities\Devices\Device;

class ManageDevicesController extends Controller
{
    public function index()
    {
        return view('manage.dashboard.index', ['pageTitle' => 'Devices', 'totalUsers' => 0, 'totalDevices' => 0, 'newUsersLast7Days' => 0, 'newUsersToday' => 0]); // Temporary redirect or view
    }
}
