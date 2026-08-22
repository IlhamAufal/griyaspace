<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $rooms = \App\Models\Room::where('status', 'active')->get();

        return view('pages.calendar.index', compact('rooms'));
    }

    public function events(Request $request)
    {
        $from = $request->input('from', $request->input('start'));
        $to = $request->input('to', $request->input('end'));

        if (!$from || !$to) {
            return response()->json(['error' => 'Parameter from/start and to/end are required.'], 422);
        }

        $fromDate = date('Y-m-d', strtotime($from));
        $toDate = date('Y-m-d', strtotime($to));

        $query = Booking::with(['room', 'organization'])
            ->where('booking_date', '>=', $fromDate)
            ->where('booking_date', '<=', $toDate)
            ->where('status', '!=', 'cancelled');

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $user = $request->user();

        if ($user->isOrganization()) {
            $bookings = $query->get()->map(function ($booking) use ($user) {
                $isOwn = $booking->organization_id === $user->organization_id;
                return [
                    'id' => $booking->id,
                    'title' => $isOwn ? $booking->activity_name : 'Terisi',
                    'start' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->start_time,
                    'end' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->end_time,
                    'color' => $isOwn ? '#1d4ed8' : '#9ca3af',
                    'extendedProps' => [
                        'room' => $booking->room->name,
                        'status' => $booking->status,
                        'is_own' => $isOwn,
                    ],
                ];
            });
        } else {
            $bookings = $query->get()->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->activity_name,
                    'start' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->start_time,
                    'end' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->end_time,
                    'color' => match ($booking->status) {
                        'approved' => '#16a34a',
                        'submitted' => '#eab308',
                        'revision' => '#f97316',
                        'rejected' => '#dc2626',
                        default => '#6b7280',
                    },
                    'extendedProps' => [
                        'room' => $booking->room->name,
                        'organization' => $booking->organization->name,
                        'status' => $booking->status,
                    ],
                ];
            });
        }

        return response()->json($bookings);
    }
}
