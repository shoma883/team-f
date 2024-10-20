<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\GeminiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventories関連のルート
    Route::resource('inventories', InventoryController::class);

    // Gemini関連のルート
    Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
    Route::post('/gemini', [GeminiController::class, 'entry'])->name('gemini.entry');
    Route::post('/gemini/save', [GeminiController::class, 'save'])->name('gemini.save');
});

require __DIR__.'/auth.php';
