<?php

namespace App\Http\Controllers;

use App\Models\Permit;

class PublicController extends Controller
{
    public function verify($token)
    {
        $permit = Permit::with(['booking.room', 'booking.organization'])
            ->where('verification_token', $token)
            ->first();

        if (!$permit) {
            return view('pages.public.verify', [
                'status' => 'not_found',
                'permit' => null,
            ]);
        }

        $booking = $permit->booking;
        $now = now();

        if ($permit->status === 'revoked') {
            $status = 'revoked';
        } elseif ($permit->status === 'expired') {
            $status = 'expired';
        } elseif ($booking->booking_date->isPast()) {
            $status = 'expired';
        } elseif ($booking->booking_date->isToday()) {
            $status = 'active';
        } else {
            $status = 'upcoming';
        }

        return view('pages.public.verify', compact('permit', 'status'));
    }
}
