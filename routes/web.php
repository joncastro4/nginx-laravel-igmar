<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── 2FA setup routes ──────────────────────────────────────────────────
// Intentionally outside the 2fa middleware.
// A user arriving here from registration has no secret yet.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/2fa/setup',   [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
});

// ── OTP verification endpoint (used by the package middleware) ─────────
Route::middleware(['auth', '2fa'])->group(function () {
    Route::post('/2fa', function () {
        return redirect()->intended(route('dashboard'));
    })->name('2fa');
});

// ── Protected app routes ───────────────────────────────────────────────
Route::middleware(['auth', 'verified', '2fa.required', '2fa'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';