<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\TerapiController;
use App\Http\Controllers\TerapisController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ChatbotController;

Route::resource('kategoris', KategoriController::class);
Route::resource('terapis', TerapiController::class);
Route::resource('users', UserController::class);
Route::resource('jadwals', JadwalController::class);


Route::get('/home', [FrontController::class, 'index'])->name('home');
Route::get('/', [FrontController::class, 'loadscreen'])->name('loadscreen');
Route::get('/detail-terapis/{id}', [TerapisController::class, 'detail'])->name('detail-terapis');
Route::get('/daftar-terapis', [TerapisController::class, 'daftar'])->name('daftar-terapis');
// Route::get('/home', [UserController::class, 'index']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tambahan routes untuk soft deleted:
Route::get('users/deleted', [UserController::class, 'deleted'])->name('users.deleted');
Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

Route::get('terapis/deleted', [UserController::class, 'deleted'])->name('terapis.deleted');
Route::post('terapis/{id}/restore', [TerapiController::class, 'restore'])->name('terapis.restore');
Route::delete('terapis/{id}/force-delete', [TerapiController::class, 'forceDelete'])->name('terapis.forceDelete');

Route::get('jadwals/deleted', [UserController::class, 'deleted'])->name('jadwals.deleted');
Route::post('jadwals/{id}/restore', [JadwalController::class, 'restore'])->name('jadwals.restore');
Route::delete('jadwals/{id}/force-delete', [JadwalController::class, 'forceDelete'])->name('jadwals.forceDelete');


Route::get('/booking/{kode_terapi}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking/{kode_terapi}', [BookingController::class, 'store'])->name('booking.store');

Route::get('/therapists', [FrontController::class, 'therapists'])->name('therapists.index');

Route::get('/pilih-terapis', [FrontController::class, 'therapists'])->name('terapis.cards');

Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
// web.php
Route::get('/api/jadwal/by-terapis/{kode_terapi}', [BookingController::class, 'getJadwalByTerapis']);
Route::get('/reservasi', [BookingController::class, 'form'])->name('reservasi');



Route::post('/booking/{id}/accept', [BookingController::class, 'accept'])->name('booking.accept');
Route::post('/cancel-booking/{bookingId}', [BookingController::class, 'cancel'])->name('booking.cancel');


// Payment Routes
Route::get('payment/create/{kode_booking}', [PembayaranController::class, 'createPayment'])->name('payment.create');

Route::post('/payment/store', [PembayaranController::class, 'storePayment'])->name('payment.store');
Route::get('/payment/{kode_booking}', [PembayaranController::class, 'viewPayment'])->name('payment.view');

// Payment Confirmation Routes (for level 2 and level 3 users)
Route::get('/payment/approve/{paymentId}', [PembayaranController::class, 'approvePayment'])->name('payment.approve');
Route::get('/payment/reject/{paymentId}', [PembayaranController::class, 'rejectPayment'])->name('payment.reject');

Route::get('/histori', [BookingController::class, 'history'])->name('histori');
Route::get('/booking-detail/{id}', [BookingController::class, 'show'])->name('booking.detail');

Route::post('/api/chat', [ChatbotController::class, 'chat']);
