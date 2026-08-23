<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $pendingCount = Booking::where('status', 'submitted')->count();
        $approvedCount = Booking::where('status', 'approved')->count();
        $activeRooms = Room::where('status', 'active')->count();
        $todayBookings = Booking::where('booking_date', today())
            ->where('status', 'approved')
            ->count();

        $recentSubmissions = Booking::with(['room', 'organization', 'submittedBy'])
            ->latest()
            ->limit(5)
            ->get();

        $upcomingBookings = Booking::where('booking_date', '>=', today())
            ->where('status', 'approved')
            ->with(['room', 'organization'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return view('pages.dashboard.admin', compact(
            'pendingCount',
            'approvedCount',
            'activeRooms',
            'todayBookings',
            'recentSubmissions',
            'upcomingBookings'
        ));
    }
}
