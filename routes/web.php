<?php

use App\Events\LocationUpdated;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkZone\IncidentController;
use App\Http\Controllers\WorkZonesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [GuestController::class, 'index'])->name('welcome');
Route::get('/about', [GuestController::class, 'about'])->name('about');

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('register-user', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'show_profile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit_profile'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'update_profile'])->name('profile.update');
    Route::get('/work-zones/map', [UserController::class, 'map'])->name('work_zones.map');
    Route::resource('/work-zones', WorkZonesController::class, ['names' => 'work_zones'])->except('update');
    Route::post('/work-zones/{work_zone}', [WorkZonesController::class, 'update'])->name('work_zones.update');
    Route::prefix('work-zones/{work_zone}')->name('work_zones.')->group(function () {
        Route::get('/dashboard', [WorkZonesController::class, 'dashboard'])->name('work_zone.dashboard');
        Route::get('/map', [WorkZonesController::class, 'map'])->name('work_zone.map');
        Route::resource('/incidents', IncidentController::class);
        Route::resource('/devices', IncidentController::class);
    });
});

require __DIR__ . '/auth.php';
