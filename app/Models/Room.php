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
}
