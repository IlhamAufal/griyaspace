<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Organization;
use App\Models\BookingHistory;
use App\Models\BookingDocument;
use App\Models\Permit;
use App\Enums\BookingStatus;
use App\Services\BookingService;
use App\Services\PermitPdfService;
use App\Exceptions\BookingConflictException;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Booking::with(['room', 'organization', 'submittedBy']);

        if ($user->isOrganization()) {
            $query->where('organization_id', $user->organization_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('activity_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('pages.bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $rooms = Room::where('status', 'active')->get();
        $user = $request->user();
        $users = $user->isAdmin()
            ? User::where('is_active', true)->whereHas('role', fn($q) => $q->where('slug', '!=', 'admin'))->with(['organization', 'role'])->get()
            : collect();
        $organizations = Organization::where('is_active', true)->get();

        return view('pages.bookings.create', compact('rooms', 'user', 'users', 'organizations'));
    }

    public function store(Request $request, BookingService $bookingService)
    {
        $user = $request->user();

        // Support start_date & end_date format
        if ($request->filled('start_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date) : $startDate->copy();

            if ($endDate->lt($startDate)) {
                return back()
                    ->withErrors(['end_date' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.'])
                    ->withInput()
                    ->with('conflict_step', 2);
            }

            $dates = [];
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $dates[] = $current->format('Y-m-d');
                $current->addDay();
            }

            $request->merge(['booking_dates' => $dates]);
        }

        // Accept either booking_dates[] (multi-day) or booking_date (single)
        $hasMultiDay = $request->has('booking_dates') && is_array($request->booking_dates);

        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'activity_name' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'participant_count' => 'required|integer|min:1',
            'person_in_charge' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf|max:10240',
            'user_id' => $user->isAdmin() ? 'nullable|exists:users,id' : 'nullable',
            'organization_id' => 'nullable|exists:organizations,id',
        ];

        $messages = [
            'booking_dates.max' => 'Maksimal durasi peminjaman adalah 3 hari.',
            'booking_dates.*.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lalu.',
            'booking_date.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lalu.',
            'end_time.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ];

        if ($hasMultiDay) {
            $rules['booking_dates'] = 'required|array|min:1|max:3';
            $rules['booking_dates.*'] = 'required|date|after_or_equal:today';
        } else {
            $rules['booking_date'] = 'required|date|after_or_equal:today';
        }

        $validated = $request->validate($rules, $messages);

        // Normalize: always pass booking_dates array to service
        if (!$hasMultiDay) {
            $validated['booking_dates'] = [$validated['booking_date']];
        }
        unset($validated['booking_date']);

        try {
            $result = $bookingService->create(
                $validated,
                $user,
                $request->file('document')
            );

            // $result is either a single Booking or an array of Bookings
            $booking = is_array($result) ? $result[0] : $result;

            $count = is_array($result) ? count($result) : 1;
            $message = $count > 1
                ? "Pengajuan {$count} hari berhasil dikirim."
                : 'Pengajuan berhasil dikirim.';

            return redirect()->route('bookings.show', $booking)->with('success', $message);
        } catch (BookingConflictException $e) {
            return back()
                ->withErrors([
                    'schedule_conflict' => $e->getMessage(),
                    'start_time' => $e->getMessage(),
                ])
                ->with('conflict_step', 2)
                ->withInput();
        } catch (InvalidArgumentException $e) {
            return back()
                ->withErrors(['participant_count' => $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['room', 'organization', 'submittedBy', 'approvedBy', 'documents', 'permit', 'history.changedBy']);

        $rooms = collect();
        if ($booking->status === BookingStatus::Revision && !auth()->user()->isAdmin()) {
            $rooms = Room::where('status', 'active')->get();
        }

        return view('pages.bookings.show', compact('booking', 'rooms'));
    }

    public function edit(Booking $booking)
    {
        if (!$booking->isEditable()) {
            abort(403, 'Booking tidak dapat diedit.');
        }

        $rooms = Room::where('status', 'active')->get();

        return view('pages.bookings.edit', compact('booking', 'rooms'));
    }

    public function update(Request $request, Booking $booking)
    {
        if (!$booking->isEditable()) {
            abort(403, 'Booking tidak dapat diedit.');
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'activity_name' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'participant_count' => 'required|integer|min:1',
            'person_in_charge' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $room = Room::find($validated['room_id']);

        if ($validated['participant_count'] > $room->capacity) {
            return back()->withErrors(['participant_count' => 'Jumlah peserta melebihi kapasitas ruangan.'])->withInput();
        }

        $hasConflict = Booking::where('room_id', $validated['room_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where('id', '!=', $booking->id)
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        if ($hasConflict) {
            return back()->withErrors(['start_time' => 'Jadwal bentrok dengan booking lain.'])->withInput();
        }

        DB::beginTransaction();

        try {
            $oldStatus = $booking->status;
            $booking->update($validated);

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $storageKey = 'bookings/' . $booking->id . '/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('private', $storageKey);

                $booking->documents()->update(['is_current' => false]);

                $lastVersion = $booking->documents()->max('version') ?? 0;

                BookingDocument::create([
                    'booking_id' => $booking->id,
                    'version' => $lastVersion + 1,
                    'storage_key' => $storageKey,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'checksum' => hash_file('sha256', $file->getPathname()),
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now(),
                    'is_current' => true,
                ]);
            }

            if ($oldStatus === BookingStatus::Revision) {
                $booking->update(['status' => BookingStatus::Submitted->value]);
            }

            BookingHistory::create([
                'booking_id' => $booking->id,
                'previous_status' => $oldStatus instanceof BookingStatus ? $oldStatus->value : $oldStatus,
                'new_status' => $booking->status instanceof BookingStatus ? $booking->status->value : $booking->status,
                'note' => 'Data diperbarui.',
                'changed_by' => auth()->id(),
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking)->with('success', 'Booking berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui booking.'])->withInput();
        }
    }

    public function cancel(Booking $booking)
    {
        if (!$booking->canBeCancelled()) {
            abort(403, 'Booking tidak dapat dibatalkan.');
        }

        $oldStatus = $booking->status;
        $booking->update([
            'status' => BookingStatus::Cancelled,
            'cancelled_at' => now(),
        ]);

        BookingHistory::create([
            'booking_id' => $booking->id,
            'previous_status' => $oldStatus instanceof BookingStatus ? $oldStatus->value : $oldStatus,
            'new_status' => BookingStatus::Cancelled->value,
            'note' => 'Dibatalkan oleh pengguna.',
            'changed_by' => auth()->id(),
            'created_at' => now(),
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function decision(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject,revision',
            'admin_note' => $request->input('action') === 'approve' ? 'nullable|string' : 'required|string',
        ]);

        $oldStatus = $booking->status;

        $newStatus = match ($validated['action']) {
            'approve' => BookingStatus::Approved,
            'reject' => BookingStatus::Rejected,
            'revision' => BookingStatus::Revision,
        };

        $booking->update([
            'status' => $newStatus,
            'admin_note' => $validated['admin_note'],
            'approved_by' => $validated['action'] === 'approve' ? auth()->id() : null,
            'decided_at' => now(),
        ]);

        BookingHistory::create([
            'booking_id' => $booking->id,
            'previous_status' => $oldStatus instanceof BookingStatus ? $oldStatus->value : $oldStatus,
            'new_status' => $newStatus->value,
            'note' => $validated['admin_note'],
            'changed_by' => auth()->id(),
            'created_at' => now(),
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Keputusan berhasil disimpan.');
    }

    public function history(Booking $booking)
    {
        $history = $booking->history()->with('changedBy')->latest('created_at')->get();
        return view('pages.bookings.history', compact('booking', 'history'));
    }

    public function historyAll(Request $request)
    {
        $user = $request->user();
        $query = Booking::with(['room', 'submittedBy', 'permit']);

        if (!$user->isAdmin()) {
            $query->where('submitted_by', $user->id);
        }

        $query->whereIn('status', ['approved', 'rejected', 'cancelled']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('activity_name', 'like', "%{$search}%");
            });
        }

        if ($user->isAdmin() && $request->filled('user_id')) {
            $query->where('submitted_by', $request->user_id);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        $users = $user->isAdmin() ? User::where('is_active', true)->get() : collect();

        return view('pages.bookings.history-all', compact('bookings', 'users'));
    }

    public function downloadPermit(Booking $booking)
    {
        $permit = $booking->permit;
        abort_unless($permit && $permit->pdf_storage_key, 404);

        $path = storage_path('app/private/' . $permit->pdf_storage_key);
        abort_unless(file_exists($path), 404);

        return response()->download($path, "Izin-{$permit->permit_number}.pdf");
    }

    public function konfirmasi(Request $request)
    {
        $query = Booking::with(['room', 'submittedBy'])
            ->whereIn('status', ['submitted', 'revision']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('activity_name', 'like', "%{$search}%")
                  ->orWhereHas('submittedBy', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('submitted_by', $request->user_id);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        $users = User::where('is_active', true)->get();

        return view('pages.bookings.konfirmasi', compact('bookings', 'users'));
    }

    public function progress(Request $request)
    {
        $query = Booking::with(['room', 'submittedBy'])
            ->where('submitted_by', auth()->id())
            ->whereIn('status', ['submitted', 'revision']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('activity_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('pages.bookings.progress', compact('bookings'));
    }

    public function generatePermit(Request $request, Booking $booking)
    {
        abort_unless($booking->status === BookingStatus::Approved, 403);
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'booking_number' => 'nullable|string|max:255',
            'permit_number' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['booking_number'])) {
            $booking->update(['booking_number' => $validated['booking_number']]);
        }

        $permit = Permit::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'status' => 'valid',
                'issued_at' => now(),
                'template_version' => '1.0',
            ]
        );

        if (!empty($validated['permit_number'])) {
            $permit->update(['permit_number' => $validated['permit_number']]);
        }

        try {
            $pdfService = new PermitPdfService();
            $pdfContent = $pdfService->generatePdf($permit);

            Storage::disk('local')->put("permits/{$permit->id}.pdf", $pdfContent);
            $permit->update(['pdf_storage_key' => "permits/{$permit->id}.pdf"]);

            return redirect()->route('bookings.show', $booking)->with('success', 'Dokumen izin berhasil digenerate.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal generate dokumen: ' . $e->getMessage()]);
        }
    }
}
