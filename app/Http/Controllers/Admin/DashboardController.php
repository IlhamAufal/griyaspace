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
        $totalBookings = Booking::count();

        // 6 Bulan Terakhir untuk Chart Tren (Navy & Kuning & Toska)
        $monthlyLabels = [];
        $monthlySubmitted = [];
        $monthlyApproved = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            $monthlyLabels[] = $date->translatedFormat('M Y');
            $monthlySubmitted[] = Booking::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $monthlyApproved[] = Booking::whereYear('booking_date', $year)
                ->whereMonth('booking_date', $month)
                ->where('status', 'approved')
                ->count();
        }

        // Ruangan Terpopuler (Top 5 Rooms by Approved Bookings)
        $topRooms = Room::withCount(['bookings' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        $topRoomLabels = $topRooms->pluck('name')->toArray();
        $topRoomCounts = $topRooms->pluck('bookings_count')->toArray();

        // Distribusi Status Pengajuan
        $revisionCount = Booking::where('status', 'revision')->count();
        $rejectedCount = Booking::whereIn('status', ['rejected', 'cancelled'])->count();

        $statusData = [
            'labels' => ['Disetujui', 'Menunggu Review', 'Perlu Revisi', 'Ditolak / Batal'],
            'series' => [$approvedCount, $pendingCount, $revisionCount, $rejectedCount],
        ];

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
            'revisionCount',
            'rejectedCount',
            'activeRooms',
            'todayBookings',
            'totalBookings',
            'monthlyLabels',
            'monthlySubmitted',
            'monthlyApproved',
            'topRoomLabels',
            'topRoomCounts',
            'statusData',
            'recentSubmissions',
            'upcomingBookings'
        ));
    }
}
