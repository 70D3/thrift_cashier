<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $count_produks = Produk::count();
        $count_cashiers = User::where('role', 'kasir')->count();
        $income_this_day = Transaction::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))->whereDate('created_at', now()->format('Y-m-d'))->sum('total');
        $transaction_total = Transaction::whereDate('created_at', now()->format('Y-m-d'))->count();
        return view('pages.admin.dashboard', [
            'title' => 'Dashboard',
            'count_produks' => $count_produks,
            'count_cashiers' => $count_cashiers,
            'income_this_day' => $income_this_day,
            'transaction_total' => $transaction_total,
        ]);
    }
}
