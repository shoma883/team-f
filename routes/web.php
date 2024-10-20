<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

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

    
  Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
  Route::get('inventories/input', [InventoryController::class, 'input']);
  Route::post('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');

 
  Route::resource('inventories', InventoryController::class);
  Route::get('/inventories/stock', [InventoryController::class, 'stock'])->name('inventories.stock');
  Route::get('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');

	Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
	Route::post('/gemini', [GeminiController::class, 'entry'])->name('entry');

	Route::resource('inventories', InventoryController::class);
	Route::get('/inventories/stock', [InventoryController::class, 'stock'])->name('inventories.stock');
	Route::get('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');
	Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
	Route::get('inventories/input', [InventoryController::class, 'input']);
	Route::post('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');
	Route::get('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');
	Route::get('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');
	Route::get('/inventories/input', [InventoryController::class, 'input'])->name('inventories.input');


	Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
	Route::post('/gemini', [GeminiController::class, 'entry'])->name('gemini.entry');
	Route::post('/gemini/inventory', [GeminiController::class, 'inventory'])->name('gemini.inventory');
	Route::post('/gemini/save', [GeminiController::class, 'save'])->name('gemini.save');

});

require __DIR__ . '/auth.php';
