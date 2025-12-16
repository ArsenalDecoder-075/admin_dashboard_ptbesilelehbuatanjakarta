<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('welcome');
// });

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Supplier routes
    Route::resource('suppliers', SupplierController::class);

    // Raw Material routes
    Route::resource('raw-materials', RawMaterialController::class);

    // Purchase Order routes
    Route::resource('purchase-orders', PurchaseOrderController::class);
});

require __DIR__.'/auth.php';
