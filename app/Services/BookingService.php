<?php

namespace App\Services;

use App\Exceptions\BookingConflictException;
use App\Models\Booking;
use App\Models\BookingDocument;
use App\Models\BookingHistory;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BookingService
{
    /**
     * Create a new booking with concurrency lock to avoid race condition conflicts.
     *
     * @param array $data
     * @param User $actor
     * @param UploadedFile|null $document
     * @return Booking
     * @throws BookingConflictException|InvalidArgumentException|\Throwable
     */
    public function create(array $data, User $actor, ?UploadedFile $document = null): Booking
    {
        return DB::transaction(function () use ($data, $actor, $document) {
            // Lock room row to serialize concurrent booking operations for this room
            $room = Room::where('id', $data['room_id'])->lockForUpdate()->firstOrFail();

            if ((int) $data['participant_count'] > $room->capacity) {
                throw new InvalidArgumentException("Jumlah peserta ({$data['participant_count']}) melebihi kapasitas ruangan ({$room->capacity}).");
            }

            // Check if there is any overlapping booking with pessimistic lock
            $hasConflict = Booking::where('room_id', $data['room_id'])
                ->where('booking_date', $data['booking_date'])
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'rejected')
                ->where(function ($query) use ($data) {
                    $query->where('start_time', '<', $data['end_time'])
                          ->where('end_time', '>', $data['start_time']);
                })
                ->lockForUpdate()
                ->exists();

            if ($hasConflict) {
                throw new BookingConflictException('Jadwal bentrok dengan peminjaman lain pada ruangan dan tanggal/waktu yang dipilih.');
            }

            // Determine submitter and organization
            $submitter = $actor;
            if ($actor->isAdmin() && !empty($data['user_id'])) {
                $submitter = User::findOrFail($data['user_id']);
            }

            $organizationId = $data['organization_id'] ?? $submitter->organization_id;
            if (!$organizationId && $submitter->organization_id) {
                $organizationId = $submitter->organization_id;
            }

            if (!$organizationId && $actor->isAdmin()) {
                // If submitter still has no organization, pick first active or throw
                $firstOrg = \App\Models\Organization::first();
                $organizationId = $firstOrg ? $firstOrg->id : 1;
            }

            // Create booking record
            $booking = Booking::create([
                'room_id' => $data['room_id'],
                'organization_id' => $organizationId,
                'booking_date' => $data['booking_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'activity_name' => $data['activity_name'],
                'purpose' => $data['purpose'] ?? '',
                'participant_count' => (int) $data['participant_count'],
                'person_in_charge' => $data['person_in_charge'],
                'contact_phone' => $data['contact_phone'],
                'status' => 'submitted',
                'submitted_by' => $submitter->id,
                'submitted_at' => now(),
            ]);

            // Save uploaded document if provided
            if ($document) {
                $storageKey = 'bookings/' . $booking->id . '/' . Str::random(40) . '.' . $document->getClientOriginalExtension();
                $document->storeAs('private', $storageKey);

                BookingDocument::create([
                    'booking_id' => $booking->id,
                    'version' => 1,
                    'storage_key' => $storageKey,
                    'original_filename' => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType(),
                    'file_size' => $document->getSize(),
                    'checksum' => hash_file('sha256', $document->getPathname()),
                    'uploaded_by' => $actor->id,
                    'uploaded_at' => now(),
                    'is_current' => true,
                ]);
            }

            // Record initial history timeline
            BookingHistory::create([
                'booking_id' => $booking->id,
                'previous_status' => '-',
                'new_status' => 'submitted',
                'note' => $actor->isAdmin() && $submitter->id !== $actor->id
                    ? 'Pengajuan baru oleh admin untuk ' . $submitter->name . '.'
                    : 'Pengajuan baru.',
                'changed_by' => $actor->id,
                'created_at' => now(),
            ]);

            return $booking;
        });
    }
}
