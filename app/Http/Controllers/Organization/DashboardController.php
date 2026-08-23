<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Permit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $totalMyBookings = Booking::where('organization_id', $orgId)->count();

        // 6 Bulan Terakhir untuk Riwayat Aktivitas Organisasi
        $monthlyLabels = [];
        $monthlySubmitted = [];
        $monthlyApproved = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            $monthlyLabels[] = $date->translatedFormat('M Y');
            $monthlySubmitted[] = Booking::where('organization_id', $orgId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $monthlyApproved[] = Booking::where('organization_id', $orgId)
                ->whereYear('booking_date', $year)
                ->whereMonth('booking_date', $month)
                ->where('status', 'approved')
                ->count();
        }

        // Distribusi Status Pengajuan Organisasi
        $revisionCount = Booking::where('organization_id', $orgId)->where('status', 'revision')->count();
        $rejectedCount = Booking::where('organization_id', $orgId)->whereIn('status', ['rejected', 'cancelled'])->count();

        $statusData = [
            'labels' => ['Disetujui', 'Menunggu', 'Revisi', 'Ditolak/Batal'],
            'series' => [$myApprovedCount, $myPendingCount, $revisionCount, $rejectedCount],
        ];

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
            'totalMyBookings',
            'monthlyLabels',
            'monthlySubmitted',
            'monthlyApproved',
            'statusData',
            'nearestBookings'
        ));
    }
}
