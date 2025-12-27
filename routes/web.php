<?php

use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\IncomeController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('cashier.home');
Route::get('/transaksi-sukses/{id}', [HomeController::class, 'success'])->name('cashier.success');


Route::get('order-list', [HomeController::class, 'order_list'])->name('cashier.order-list');
Route::post('order-list/{id}/update', [HomeController::class, 'order_update'])->name('cashier.order-update');
Route::get('print/{id}', [HomeController::class, 'print'])->name('cashier.print');


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('produk', ProdukController::class);
    Route::resource('kasir', CashierController::class);
    Route::resource('pendapatan', IncomeController::class);
});

// (Midtrans webhook removed)
