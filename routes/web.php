<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PublicController;

// Public
Route::get('/verifikasi/{token}', [PublicController::class, 'verify'])->name('public.verify');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Kalender
    Route::prefix('kalender')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/events', [CalendarController::class, 'events'])->name('calendar.events');
    });

    // Pengajuan
    Route::prefix('pengajuan')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/baru', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/konfirmasi', [BookingController::class, 'konfirmasi'])->name('bookings.konfirmasi')->middleware('role:admin');
        Route::get('/riwayat', [BookingController::class, 'historyAll'])->name('bookings.historyAll');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/{booking}/decision', [BookingController::class, 'decision'])->name('bookings.decision');
        Route::get('/{booking}/riwayat', [BookingController::class, 'history'])->name('bookings.history');
    });

    // Ruangan (index & show bisa diakses semua role)
    Route::prefix('ruangan')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/{room}', [RoomController::class, 'show'])->name('rooms.show');
    });

    // Admin only
    Route::middleware(['role:admin'])->group(function () {

        // Ruangan (admin only)
        Route::prefix('ruangan')->group(function () {
            Route::get('/create', [RoomController::class, 'create'])->name('rooms.create');
            Route::post('/', [RoomController::class, 'store'])->name('rooms.store');
            Route::get('/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
            Route::put('/{room}', [RoomController::class, 'update'])->name('rooms.update');
            Route::delete('/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
        });

        // Organisasi
        Route::prefix('organisasi')->group(function () {
            Route::get('/', [OrganizationController::class, 'index'])->name('organizations.index');
            Route::get('/create', [OrganizationController::class, 'create'])->name('organizations.create');
            Route::post('/', [OrganizationController::class, 'store'])->name('organizations.store');
            Route::get('/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
            Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
            Route::put('/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
            Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
        });

        // Role
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        // Pengguna
        Route::prefix('pengguna')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        });

    });

});
