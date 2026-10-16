<?php

namespace App\Console\Commands;

use App\Services\BookingAutoRejectService;
use Illuminate\Console\Command;

class RejectExpiredBookings extends Command
{
    protected $signature = 'app:auto-reject-expired';

    protected $description = 'Tolak otomatis pengajuan yang tanggal bookingnya sudah melewati hari ini';

    public function handle(BookingAutoRejectService $service): int
    {
        $count = $service->handle();

        $this->info($count === 0
            ? 'Tidak ada pengajuan kedaluwarsa.'
            : "{$count} pengajuan otomatis ditolak.");

        return self::SUCCESS;
    }
}
