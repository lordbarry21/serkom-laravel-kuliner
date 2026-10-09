<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Pemesanan Makanan (Serkom LSP)
|--------------------------------------------------------------------------
*/

// 1. Sisi Pelanggan (Publik / Tanpa Login)
Route::get('/', [OrderController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout');

// 2. Dashboard Monitoring Pesanan (Wajib Login Admin)
Route::get('/dashboard', [OrderController::class, 'adminDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Panel Administrasi Terproteksi (Laravel Breeze Auth)
Route::middleware('auth')->group(function () {
    // Akun Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Update Status Transaksi Pesanan (PATCH)
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // CRUD Lengkap Master Makanan (Resource Controller)
    Route::resource('/admin/foods', FoodController::class);
});

require __DIR__.'/auth.php';
