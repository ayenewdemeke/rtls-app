<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\ReadingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Post
Route::post('/phone-location', [LocationController::class, 'store']);
Route::post('/device-readings', [ReadingController::class, 'store']);
Route::post('/devices', [DeviceController::class, 'store']);
Route::post('/fused-measurements', [MeasurementController::class, 'store']);

// Get
Route::get('/devices', [DeviceController::class, 'index']);
Route::get('/device-readings', [ReadingController::class, 'index']);
Route::get('/device-readings/{device_id}', [ReadingController::class, 'showByDevice']);
Route::get('/fused-measurements', [MeasurementController::class, 'index']);
Route::get('/fused-measurements/{device_id}', [MeasurementController::class, 'showByDevice']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
