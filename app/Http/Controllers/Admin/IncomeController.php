<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Transaction::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->get();
        return view('pages.admin.incomes.index', [
            'title' => 'Pendapatan',
            'incomes' => $incomes
        ]);
    }
}
