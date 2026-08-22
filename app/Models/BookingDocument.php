<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDocument extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'version',
        'storage_key',
        'original_filename',
        'mime_type',
        'file_size',
        'checksum',
        'uploaded_by',
        'uploaded_at',
        'is_current',
    ];

    protected $casts = [
        'version' => 'integer',
        'file_size' => 'integer',
        'is_current' => 'boolean',
        'uploaded_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
