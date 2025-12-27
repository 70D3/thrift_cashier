<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.cashier.home', [
            'title' => 'Dashboard Kasir'
        ]);
    }
    public function success($id)
    {
        return view('pages.cashier.success', [
            'title' => 'Transaksi Sukses',
            'transaction' => Transaction::find($id)
        ]);
    }

    public function order_list()
    {
        return view('pages.cashier.order-list', [
            'title' => 'Order List',
            'orders' => Transaction::orderBy('id', 'DESC')->get()
        ]);
    }

    public function print($id)
    {
        return view('pages.admin.cashiers.print', [
            'title' => 'Cetak Struk',
            'transaction' => Transaction::findOrFail($id)
        ]);
    }

    public function order_update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $allowed = ['Completed', 'Cancelled', 'Waiting', 'Cart'];
        $status = $request->input('status');

        if (!in_array($status, $allowed)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $transaction = Transaction::find($id);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        $transaction->update(['status' => $status]);

        return redirect()->route('cashier.order-list')->with('success', 'Status diubah');
    }
}
