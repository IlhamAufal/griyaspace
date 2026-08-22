<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return $this->organizationDashboard($user);
    }

    private function adminDashboard($user)
    {
        $pendingCount = Booking::where('status', 'submitted')->count();
        $approvedCount = Booking::where('status', 'approved')->count();
        $activeRooms = Room::where('status', 'active')->count();
        $todayBookings = Booking::where('booking_date', today())
            ->where('status', 'approved')
            ->count();

        $upcomingBookings = Booking::where('booking_date', '>=', today())
            ->where('status', 'approved')
            ->with(['room', 'organization'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $recentSubmissions = Booking::with(['room', 'organization', 'submittedBy'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.dashboard.admin', compact(
            'pendingCount',
            'approvedCount',
            'activeRooms',
            'todayBookings',
            'upcomingBookings',
            'recentSubmissions'
        ));
    }

    private function organizationDashboard($user)
    {
        $myPendingCount = Booking::where('organization_id', $user->organization_id)
            ->where('status', 'submitted')
            ->count();

        $myApprovedCount = Booking::where('organization_id', $user->organization_id)
            ->where('status', 'approved')
            ->count();

        $myRevisionCount = Booking::where('organization_id', $user->organization_id)
            ->where('status', 'revision')
            ->count();

        $upcomingBookings = Booking::where('organization_id', $user->organization_id)
            ->where('booking_date', '>=', today())
            ->where('status', 'approved')
            ->with('room')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $recentBookings = Booking::where('organization_id', $user->organization_id)
            ->with('room')
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.dashboard.organization', compact(
            'myPendingCount',
            'myApprovedCount',
            'myRevisionCount',
            'upcomingBookings',
            'recentBookings'
        ));
    }
}
