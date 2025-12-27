<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashiers = User::where('role', 'kasir')
            ->withCount(['transactions as total_penjualan' => function ($q) {
                $q->where('status', '!=', 'Cart');
            }])
            ->orderBy('id', 'DESC')
            ->get();

        return view('pages.admin.cashiers.index', [
            'title' => 'Semua Kasir',
            'cashiers' => $cashiers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.cashiers.create', [
            'title' => 'Masukkan Kasir Baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        User::create($data);

        return redirect()->route('kasir.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.admin.cashiers.edit', [
            'title' => 'Edit Kasir',
            'item' => User::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        User::find($id)->update($data);

        return redirect()->route('kasir.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->back();
    }
}
