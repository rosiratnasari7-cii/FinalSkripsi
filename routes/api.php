<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\RelayController;
use App\Http\Controllers\SensorController;

Route::post('/sensor/store', [SensorController::class,'store']);
Route::post('/device/report', [SensorController::class,'store']); 
Route::get('/sensor/latest', [SensorController::class,'latest']);

// RELAY
Route::get('/relay/status', [RelayController::class,'status']);
Route::post('/relay/update', [RelayController::class,'update']);

// TELEGRAM
Route::post('/telegram/webhook', [RelayController::class,'telegramWebhook']);
