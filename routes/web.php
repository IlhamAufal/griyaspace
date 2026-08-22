<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicController;

// Public routes
Route::get('/verifikasi/{token}', [PublicController::class, 'verify'])->name('public.verify');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Calendar
    Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/api/kalender', [CalendarController::class, 'events'])->name('calendar.events');

    // Bookings
    Route::get('/pengajuan', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/pengajuan/baru', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/pengajuan', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/pengajuan/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/pengajuan/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/pengajuan/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/pengajuan/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/pengajuan/{booking}/decision', [BookingController::class, 'decision'])->name('bookings.decision');
    Route::get('/pengajuan/{booking}/riwayat', [BookingController::class, 'history'])->name('bookings.history');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        // Rooms
        Route::resource('ruangan', RoomController::class)->parameters([
            'ruangan' => 'room',
        ])->names([
            'index' => 'rooms.index',
            'create' => 'rooms.create',
            'store' => 'rooms.store',
            'show' => 'rooms.show',
            'edit' => 'rooms.edit',
            'update' => 'rooms.update',
            'destroy' => 'rooms.destroy',
        ]);

        // Organizations
        Route::resource('organisasi', OrganizationController::class)->parameters([
            'organisasi' => 'organization',
        ])->names([
            'index' => 'organizations.index',
            'create' => 'organizations.create',
            'store' => 'organizations.store',
            'show' => 'organizations.show',
            'edit' => 'organizations.edit',
            'update' => 'organizations.update',
            'destroy' => 'organizations.destroy',
        ]);

        // Users
        Route::resource('pengguna', UserController::class)->parameters([
            'pengguna' => 'user',
        ])->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
        Route::post('/pengguna/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    });
});
