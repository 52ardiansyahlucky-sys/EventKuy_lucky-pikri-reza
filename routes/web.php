<?php

use App\Http\Controllers\EventBudgetController;
use App\Http\Controllers\EventRundownController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === Modul Event (Mhs 1) ===
    Route::resource('events', EventController::class);

    // === Rundown (nested di dalam event) ===
    Route::post('/events/{event}/rundowns', [EventRundownController::class, 'store'])->name('rundowns.store');
    Route::put('/events/{event}/rundowns/{rundown}', [EventRundownController::class, 'update'])->name('rundowns.update');
    Route::delete('/events/{event}/rundowns/{rundown}', [EventRundownController::class, 'destroy'])->name('rundowns.destroy');

    // === Anggaran (nested di dalam event) ===
    Route::post('/events/{event}/budgets', [EventBudgetController::class, 'store'])->name('budgets.store');
    Route::put('/events/{event}/budgets/{budget}', [EventBudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/events/{event}/budgets/{budget}', [EventBudgetController::class, 'destroy'])->name('budgets.destroy');
});

require __DIR__.'/auth.php';
