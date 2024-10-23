<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\HistoryController;
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

	// Inventory Routes ===============================================================================
	Route::resource('inventories', InventoryController::class);
	Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
	Route::post('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
	Route::get('/inventories/show', [InventoryController::class, 'show'])->name('inventories.show');
	Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
	Route::put('/inventory/{id}/update', [InventoryController::class, 'update'])->name('inventory.update');
	Route::delete('/inventory/{inventory}/delete', [InventoryController::class, 'destroy'])->name('inventories.destroy');

	// Gemini Routes ===================================================================================
	Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
	Route::post('/gemini', [GeminiController::class, 'entry'])->name('gemini.entry');
	Route::post('/gemini/update', [GeminiController::class, 'update'])->name('gemini.update');
	Route::get('/gemini/inventory', [GeminiController::class, 'inventory'])->name('gemini.inventory');
	Route::post('/gemini/save', [GeminiController::class, 'save'])->name('gemini.save');
	

	// History Routes ==================================================================================
	Route::get('/histories', [HistoryController::class, 'index'])->name('histories.index');
	Route::get('/histories/{history}', [HistoryController::class, 'show'])->name('histories.show');
});

require __DIR__ . '/auth.php';
