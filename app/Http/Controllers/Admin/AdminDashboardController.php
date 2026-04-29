<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Device, Report};

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('is_approved', true)->count(),
            'pending_users' => User::where('is_approved', false)->count(),
            'total_devices'  => Device::count(),
            'online_devices' => Device::where('status', 'online')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}