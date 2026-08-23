<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Permit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $orgId = Auth::user()->organization_id;

        $myPendingCount = Booking::where('organization_id', $orgId)
            ->where('status', 'submitted')
            ->count();

        $myApprovedCount = Booking::where('organization_id', $orgId)
            ->where('status', 'approved')
            ->count();

        $myRevisionRejectedCount = Booking::where('organization_id', $orgId)
            ->whereIn('status', ['revision', 'rejected'])
            ->count();

        $validPermitsCount = Permit::whereHas('booking', function ($q) use ($orgId) {
            $q->where('organization_id', $orgId);
        })->where('status', 'valid')->count();

        $nearestBookings = Booking::where('organization_id', $orgId)
            ->where('booking_date', '>=', today())
            ->where('status', 'approved')
            ->with('room')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return view('pages.dashboard.organization', compact(
            'myPendingCount',
            'myApprovedCount',
            'myRevisionRejectedCount',
            'validPermitsCount',
            'nearestBookings'
        ));
    }
}
