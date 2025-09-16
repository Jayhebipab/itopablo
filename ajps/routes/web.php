<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;

// ==================== ROOT DEFAULT ROUTE ====================
Route::get('/', function () {
    return view('homme');
});

// ==================== AUTH ROUTES ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== DASHBOARD ROUTE ====================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ==================== SHORTCUT REDIRECT ROUTES ====================
Route::get('/reservation', fn() => redirect()->route('dashboard', ['page' => 'reservation']))->name('dashboard.reservation');
Route::get('/booking', fn() => redirect()->route('dashboard', ['page' => 'booking']))->name('dashboard.booking');
Route::get('/artist', fn() => redirect()->route('dashboard', ['page' => 'artist']))->name('dashboard.artist');
Route::get('/dashboard/users', fn() => redirect()->route('dashboard', ['page' => 'users']))->name('dashboard.users');

// ==================== BOOKING MANAGEMENT ROUTES ====================
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

// ==================== UNDER 18 ROUTES ====================
Route::get('/under18', function () {
    return view('under18');
});
// ==================== USERS CRUD ROUTES ====================
Route::resource('users', UserController::class)->only(['index', 'store', 'destroy']);

// ==================== RESERVATION CRUD ROUTES ====================
Route::resource('reservations', ReservationController::class)->except(['edit', 'show']);
Route::get('/reservations/{id}/edit-show', [ReservationController::class, 'showEditModal'])->name('reservations.edit.show');
Route::get('/reservations/clear-edit', [ReservationController::class, 'clearEdit'])->name('reservations.edit.clear');

// ==================== SUPER ADMIN AUTH ====================
Route::get('/superadmin-auth', [DashboardController::class, 'usersAuthForm'])->name('superadmin.auth.form');
Route::post('/superadmin-auth', [DashboardController::class, 'checkUsersAuth'])->name('dashboard.users.auth.check');

// ==================== Contact Form ====================
Route::post('/send-email', [ContactController::class, 'sendEmail']);
Route::get('/contact', function () {
    return view('contact');
});

// ==================== SPA Catch-All Route ====================
Route::get('/{homme}', function () {
    return view('homme');
})->where('any', '.*');
