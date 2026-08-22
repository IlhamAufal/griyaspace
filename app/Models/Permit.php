<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Permit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'permit_number',
        'verification_token',
        'status',
        'pdf_storage_key',
        'issued_at',
        'revoked_at',
        'revocation_reason',
        'template_version',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Permit $permit) {
            if (empty($permit->permit_number)) {
                $permit->permit_number = static::generatePermitNumber();
            }
            if (empty($permit->verification_token)) {
                $permit->verification_token = Str::random(64);
            }
        });
    }

    public static function generatePermitNumber(): string
    {
        $prefix = 'IZN-' . now()->format('Ymd') . '-';
        $lastPermit = static::where('permit_number', 'like', $prefix . '%')
            ->orderByDesc('permit_number')
            ->first();

        if ($lastPermit) {
            $lastSequence = (int) substr($lastPermit->permit_number, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }
}
