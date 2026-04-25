<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\ServiceCallController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;

// Protected app pages
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomePage::class, 'index'])->name('pages-home');
    Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');
    Route::get('/service-calls', [ServiceCallController::class, 'index'])->name('service-calls.index');
    Route::get('/service-calls/create', [ServiceCallController::class, 'create'])->name('service-calls.create');
    Route::post('/service-calls', [ServiceCallController::class, 'store'])->name('service-calls.store');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/profile/{customer}', [CustomerController::class, 'profile'])->name('customers.profile');

    Route::post('/customers/{customer}/vehicles', [VehicleController::class, 'store'])->name('customers.vehicles.store');
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::patch('/vehicles/{vehicle}/archive', [VehicleController::class, 'archive'])->name('vehicles.archive');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
});

// Test that roles work by creating a route that only users with the "admin" role can access
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin-test', function () {
        return 'Admin role check passed.';
    });
});

// Public routes
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');