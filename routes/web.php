<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\DashboardController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('cashier.home');
//Route::get('/cashier/snapshot/{id}', [HomeController::class, 'snapshot'])->name('cashier.snapshot');
Route::get('/transaksi-sukses/{id}', [HomeController::class, 'success'])->name('cashier.success');
// routes_Midtrans
//Route::post('/midtrans/snap/{transaction_id}', [HomeController::class, 'snapshot']);
Route::post('/midtrans/callback', [HomeController::class, 'callback']);
Route::get('/payment/success', [HomeController::class, 'success'])->name('payment.success');
Route::post('/midtrans/notification', [MidtransController::class, 'notification']);



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
