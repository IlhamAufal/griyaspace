<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Organization\DashboardController as OrgDashboard;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return app(AdminDashboard::class)();
        }

        return app(OrgDashboard::class)();
    }
}
