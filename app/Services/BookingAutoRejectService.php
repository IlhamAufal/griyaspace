<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BookingAutoRejectService
{
    public const REASON = 'Pengajuan ini ditolak otomatis oleh sistem karena tanggal booking sudah melewati batas waktu yang ditentukan.';

    public function handle(): int
    {
        $expiredBookings = Booking::query()
            ->where('status', BookingStatus::Submitted->value)
            ->where('booking_date', '<', now()->toDateString())
            ->get();

        if ($expiredBookings->isEmpty()) {
            return 0;
        }

        $actor = $this->systemActor();

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => BookingStatus::Rejected,
                'admin_note' => self::REASON,
                'decided_at' => now(),
            ]);

            if ($actor) {
                BookingHistory::create([
                    'booking_id' => $booking->id,
                    'previous_status' => BookingStatus::Submitted->value,
                    'new_status' => BookingStatus::Rejected->value,
                    'note' => self::REASON,
                    'changed_by' => $actor->id,
                    'created_at' => now(),
                ]);
            }
        }

        if (! $actor) {
            Log::warning('Auto-reject: riwayat booking tidak dicatat karena tidak ada user aktif.');
        }

        return $expiredBookings->count();
    }

    private function systemActor(): ?User
    {
        return User::query()
            ->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('slug', 'admin'))
            ->orderBy('id')
            ->first() ?? User::query()->where('is_active', true)->orderBy('id')->first();
    }
}
