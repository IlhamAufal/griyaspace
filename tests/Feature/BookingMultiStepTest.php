<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\BookingDocument;
use App\Enums\BookingStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Setup roles
    $this->orgRole = Role::firstOrCreate(['slug' => 'organization'], [
        'name' => 'Organisasi',
        'description' => 'Role Organisasi',
    ]);

    $this->org = Organization::firstOrCreate(['abbreviation' => 'TEST-ORG'], [
        'name' => 'Organisasi Mahasiswa Test',
        'leader_name' => 'Ketua Test',
        'phone' => '08123456789',
        'is_active' => true,
    ]);

    $this->user = User::firstOrCreate(['email' => 'user.test@griyaspace.test'], [
        'name' => 'User Test Ormawa',
        'username' => 'usertest',
        'password' => bcrypt('password'),
        'role_id' => $this->orgRole->id,
        'organization_id' => $this->org->id,
        'is_active' => true,
    ]);

    $this->room = Room::first() ?? Room::create([
        'code' => 'TEST-RM-01',
        'name' => 'Ruang Uji Coba',
        'capacity' => 50,
        'location' => 'Lantai 1',
        'facilities' => json_encode(['AC', 'Proyektor']),
        'status' => 'active',
        'open_time' => '06:00:00',
        'close_time' => '22:00:00',
    ]);

    $testActivityNames = [
        'Seminar Teknologi Masa Depan',
        'Existing Booking',
        'Conflicting Seminar',
        'Overcapacity Event',
        'Morning Meeting'
    ];
    $testBookingIds = Booking::whereIn('activity_name', $testActivityNames)->pluck('id');
    \App\Models\Permit::whereIn('booking_id', $testBookingIds)->delete();
    BookingHistory::whereIn('booking_id', $testBookingIds)->delete();
    BookingDocument::whereIn('booking_id', $testBookingIds)->delete();
    Booking::whereIn('id', $testBookingIds)->delete();
});

test('user can view 3-step booking creation page', function () {
    $response = $this->actingAs($this->user)->get(route('bookings.create'));

    $response->assertStatus(200);
    $response->assertSee('Detail & Ruangan', false);
    $response->assertSee('Jadwal & Kalender', false);
    $response->assertSee('Konfirmasi & Submit', false);
    $response->assertSee('step2-calendar');
});

test('user can submit new booking and status is submitted with timeline updated', function () {
    Storage::fake('private');

    $file = UploadedFile::fake()->create('surat-permohonan.pdf', 500, 'application/pdf');

    $response = $this->actingAs($this->user)->post(route('bookings.store'), [
        'room_id' => $this->room->id,
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '11:00',
        'activity_name' => 'Seminar Teknologi Masa Depan',
        'purpose' => 'Pengembangan wawasan AI dan Cloud Computing bagi mahasiswa.',
        'participant_count' => 30,
        'person_in_charge' => 'Budi Santoso',
        'contact_phone' => '08123456789',
        'document' => $file,
    ]);

    $response->assertSessionHasNoErrors();

    $booking = Booking::where('activity_name', 'Seminar Teknologi Masa Depan')->first();

    expect($booking)->not->toBeNull();
    expect($booking->status->value)->toBe('submitted');
    expect($booking->submitted_by)->toBe($this->user->id);
    expect($booking->organization_id)->toBe($this->org->id);

    // Verify history timeline
    $history = BookingHistory::where('booking_id', $booking->id)->first();
    expect($history)->not->toBeNull();
    expect($history->new_status)->toBe('submitted');
    expect($history->changed_by)->toBe($this->user->id);

    // Verify document record
    $doc = BookingDocument::where('booking_id', $booking->id)->first();
    expect($doc)->not->toBeNull();
    expect($doc->original_filename)->toBe('surat-permohonan.pdf');

    $response->assertRedirect(route('bookings.show', $booking));
});

test('conflicting booking detects race condition and returns back to step 2 with error', function () {
    Storage::fake('private');

    $targetDate = now()->addDays(3)->format('Y-m-d');

    // Create existing booking
    Booking::create([
        'room_id' => $this->room->id,
        'organization_id' => $this->org->id,
        'booking_date' => $targetDate,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'activity_name' => 'Existing Booking',
        'purpose' => 'Rapat',
        'participant_count' => 20,
        'person_in_charge' => 'John',
        'contact_phone' => '081234567',
        'status' => 'submitted',
        'submitted_by' => $this->user->id,
        'submitted_at' => now(),
    ]);

    // Attempt overlapping booking (11:00 - 13:00)
    $file = UploadedFile::fake()->create('surat.pdf', 300, 'application/pdf');

    $response = $this->actingAs($this->user)->post(route('bookings.store'), [
        'room_id' => $this->room->id,
        'booking_date' => $targetDate,
        'start_time' => '11:00',
        'end_time' => '13:00',
        'activity_name' => 'Conflicting Seminar',
        'purpose' => 'Kegiatan yang bentrok',
        'participant_count' => 15,
        'person_in_charge' => 'Jane',
        'contact_phone' => '089876543',
        'document' => $file,
    ]);

    $response->assertSessionHas('conflict_step', 2);
    $response->assertSessionHasErrors(['schedule_conflict', 'start_time']);

    expect(Booking::where('activity_name', 'Conflicting Seminar')->exists())->toBeFalse();
});

test('participant count exceeding room capacity is rejected', function () {
    Storage::fake('private');
    $file = UploadedFile::fake()->create('surat.pdf', 300, 'application/pdf');

    $response = $this->actingAs($this->user)->post(route('bookings.store'), [
        'room_id' => $this->room->id,
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '14:00',
        'end_time' => '16:00',
        'activity_name' => 'Overcapacity Event',
        'purpose' => 'Kegiatan melebihi kapasitas',
        'participant_count' => 9999, // Room capacity is 50
        'person_in_charge' => 'Budi',
        'contact_phone' => '0812345678',
        'document' => $file,
    ]);

    $response->assertSessionHasErrors('participant_count');
    expect(Booking::where('activity_name', 'Overcapacity Event')->exists())->toBeFalse();
});

test('calendar events endpoint returns events for selected room', function () {
    $targetDate = now()->addDays(5)->format('Y-m-d');

    Booking::create([
        'room_id' => $this->room->id,
        'organization_id' => $this->org->id,
        'booking_date' => $targetDate,
        'start_time' => '08:00',
        'end_time' => '10:00',
        'activity_name' => 'Morning Meeting',
        'purpose' => 'Rapat Pagi',
        'participant_count' => 10,
        'person_in_charge' => 'Ketua',
        'contact_phone' => '08123456',
        'status' => 'approved',
        'submitted_by' => $this->user->id,
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($this->user)->getJson(route('calendar.events', [
        'room_id' => $this->room->id,
        'from' => now()->addDays(4)->format('Y-m-d'),
        'to' => now()->addDays(6)->format('Y-m-d'),
    ]));

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'title' => 'Morning Meeting',
    ]);
});

test('user can submit booking without purpose since purpose is optional', function () {
    Storage::fake('private');
    $file = UploadedFile::fake()->create('surat-opsional.pdf', 300, 'application/pdf');

    $response = $this->actingAs($this->user)->post(route('bookings.store'), [
        'room_id' => $this->room->id,
        'booking_date' => now()->addDays(12)->format('Y-m-d'),
        'start_time' => '13:00',
        'end_time' => '15:00',
        'activity_name' => 'Acara Tanpa Tujuan Terisi',
        'purpose' => '', // Empty purpose
        'participant_count' => 20,
        'person_in_charge' => 'Siti',
        'contact_phone' => '0812345678',
        'document' => $file,
    ]);

    $response->assertSessionHasNoErrors();
    $booking = Booking::where('activity_name', 'Acara Tanpa Tujuan Terisi')->first();
    expect($booking)->not->toBeNull();
    expect($booking->status->value)->toBe('submitted');
});
