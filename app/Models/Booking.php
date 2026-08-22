<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'organization_id',
        'room_id',
        'activity_name',
        'purpose',
        'booking_date',
        'start_time',
        'end_time',
        'participant_count',
        'person_in_charge',
        'contact_phone',
        'status',
        'admin_note',
        'submitted_by',
        'approved_by',
        'submitted_at',
        'decided_at',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'participant_count' => 'integer',
        'submitted_at' => 'datetime',
        'decided_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Booking $booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = static::generateBookingNumber();
            }
        });
    }

    public static function generateBookingNumber(): string
    {
        $prefix = 'GRW-' . now()->format('Ymd') . '-';
        $lastBooking = static::where('booking_number', 'like', $prefix . '%')
            ->orderByDesc('booking_number')
            ->first();

        if ($lastBooking) {
            $lastSequence = (int) substr($lastBooking->booking_number, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BookingDocument::class);
    }

    public function currentDocument(): HasOne
    {
        return $this->hasOne(BookingDocument::class)->where('is_current', true);
    }

    public function permit(): HasOne
    {
        return $this->hasOne(Permit::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(BookingHistory::class)->latest();
    }

    public function scopeConflict($query, int $roomId, string $date, string $startTime, string $endTime, ?int $excludeId = null)
    {
        return $query->where('room_id', $roomId)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId));
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['submitted', 'revision']);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['submitted', 'revision']);
    }
}
