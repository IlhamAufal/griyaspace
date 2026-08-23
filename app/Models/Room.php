<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'code',
        'name',
        'capacity',
        'location',
        'open_time',
        'close_time',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(RoomPhoto::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getCoverPhotoUrlAttribute(): string
    {
        $firstPhoto = $this->photos->first();
        return $firstPhoto
            ? asset('storage/' . $firstPhoto->photo_path)
            : asset('images/cards/card-01.jpg');
    }
}
