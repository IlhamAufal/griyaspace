<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Permit;
use App\Services\PermitPdfService;
use Illuminate\Console\Command;

class BackfillPermits extends Command
{
    protected $signature = 'app:backfill-permits';

    protected $description = 'Generate permits for approved bookings that do not have one yet';

    public function handle(PermitPdfService $pdfService): int
    {
        $bookings = Booking::where('status', 'approved')
            ->whereDoesntHave('permit')
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking approved yang belum punya permit.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$bookings->count()} booking tanpa permit. Memproses...");

        $success = 0;
        $failed = 0;

        foreach ($bookings as $booking) {
            try {
                $permit = Permit::create([
                    'booking_id'       => $booking->id,
                    'status'           => 'valid',
                    'issued_at'        => now(),
                    'template_version' => '1.0',
                ]);

                $pdfContent = $pdfService->generatePdf($permit);
                $storageKey = 'permits/' . $permit->permit_number . '.pdf';
                \Storage::disk('local')->put($storageKey, $pdfContent);

                $permit->update(['pdf_storage_key' => $storageKey]);

                $this->line("  ✓ {$permit->permit_number} → booking #{$booking->id}");
                $success++;
            } catch (\Throwable $e) {
                $this->error("  ✗ Booking #{$booking->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("Selesai. Berhasil: {$success}, Gagal: {$failed}");
        return self::SUCCESS;
    }
}
