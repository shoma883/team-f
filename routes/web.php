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

    
  Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
  Route::get('inventories/index', [InventoryController::class, 'index']);
  Route::post('/inventories/index', [InventoryController::class, 'index'])->name('inventories.index');
 


  Route::get('/inventories/index', [InventoryController::class, 'index'])->name('inventories.index');

	Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
	Route::post('/gemini', [GeminiController::class, 'entry'])->name('entry');


	Route::resource('inventories', InventoryController::class);
  Route::put('/inventory/{id}/update', [InventoryController::class, 'update'])->name('inventory.update');
	Route::get('/inventories/show', [InventoryController::class, 'show'])->name('inventories.show');
  Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');


	Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
	Route::post('/gemini', [GeminiController::class, 'entry'])->name('gemini.entry');
	Route::get('/gemini/inventory', [GeminiController::class, 'inventory'])->name('gemini.inventory');
	Route::post('/gemini/save', [GeminiController::class, 'save'])->name('gemini.save');
    Route::get('/gemini/save', [GeminiController::class, 'save'])->name('gemini.save');
    Route::post('/gemini/update', [GeminiController::class, 'update'])->name('gemini.update');
    

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
