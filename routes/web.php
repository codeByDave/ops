<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\ServiceCallController;

// Protected app pages
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomePage::class, 'index'])->name('pages-home');
    Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');
    Route::get('/service-calls', [ServiceCallController::class, 'index'])->name('service-calls.index');
    Route::get('/service-calls/create', [ServiceCallController::class, 'create'])->name('service-calls.create');
    Route::post('/service-calls', [ServiceCallController::class, 'store'])->name('service-calls.store');
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