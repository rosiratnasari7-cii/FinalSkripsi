<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SensorController; 

Route::get('/histori', [SensorController::class, 'histori'])
    ->name('histori')
    ->middleware('auth');


// root langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// =====================
// DASHBOARD
// =====================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// =====================
// HALAMAN KONTROL RELAY (OPS I A)
// =====================
Route::get('/relay', function () {
    return view('relay'); // <-- INI PENTING
})->middleware('auth')->name('relay');

// toggle relay dari halaman relay / dashboard
Route::post('/relay/toggle', [DashboardController::class, 'toggleRelay'])
    ->middleware('auth')
    ->name('relay.toggle');

// =====================
// PROFILE
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================
// AUTH (BREEZE)
// =====================
require __DIR__.'/auth.php';
