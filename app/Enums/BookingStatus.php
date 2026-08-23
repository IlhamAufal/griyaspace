<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Revision = 'revision';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Diajukan',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
            self::Revision => 'Revisi',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function colorClasses(): string
    {
        return match ($this) {
            self::Submitted => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            self::Approved => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            self::Rejected => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            self::Revision => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
            self::Cancelled => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    public static function labels(): array
    {
        return array_column(
            array_map(fn (self $case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'value'
        );
    }

    public static function colors(): array
    {
        return array_column(
            array_map(fn (self $case) => ['value' => $case->value, 'color' => $case->colorClasses()], self::cases()),
            'color',
            'value'
        );
    }
}
