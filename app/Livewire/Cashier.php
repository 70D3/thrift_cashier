<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cashier extends Component
{
    public $produks;
    public $transaction;
    public $selectedCategory = 'Semua';
    public $customer_name;
    public $pay;
    public $total = 0;


    public function addToCart($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $produk = Produk::find($id);
        $transaction = Transaction::where('status', 'Cart')->where('cashier_id', Auth::user()->id)->first();


        if ($transaction == null) {
            Transaction::create([
                'customer_name' => 'No Name',
                'cashier_id' => Auth::user()->id,
                // 'cashier_id' => Auth::id(),
                'total' => $produk->price,
                'status' => 'Cart',
            ]);

            $last_transaction = Transaction::where('status', 'Cart')->where('cashier_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
            TransactionDetail::create([
                'transaction_id' => $last_transaction->id,
                'produk_id' => $produk->id,
                'quantity' => 1,
                'price' => $produk->price
            ]);
        } else {
            if ($transaction->details->where('produk_id', $id)->count() > 0) {
                $transaction_detail = TransactionDetail::where('transaction_id', $transaction->id)->where('produk_id', $id)->first();
                $transaction_detail->update([
                    'quantity' => $transaction_detail->quantity + 1
                ]);
            } else {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'produk_id' => $id,
                    'quantity' => 1,
                    'price' => $produk->price
                ]);
            }
        }
        $this->transaction = Transaction::where('status', 'Cart')->where('cashier_id', Auth::user()->id)->first();
    }
    public function quantity_minus($id)
    {
        $transaction_detail = TransactionDetail::find($id);
        if ($transaction_detail->quantity == 1) {
            $transaction_detail->delete();
        } else {
            $transaction_detail->update([
                'quantity' => $transaction_detail->quantity - 1
            ]);
        }
        $this->transaction = Transaction::where('status', 'Cart')->where('cashier_id', Auth::user()->id)->first();
    }

    public function delete($id)
    {
        $transaction_detail = TransactionDetail::find($id);
        if ($transaction_detail->transaction->details->count() == 1) {
            $transaction_detail->transaction->delete();
        } else {
            $transaction_detail->delete();
        }
        $this->transaction = Transaction::where('status', 'Cart')->where('cashier_id', Auth::user()->id)->first();
    }

    public function checkout()
    {
        $transaction = $this->transaction;

        // Hitung Total Belanjaan
        foreach ($transaction->details as $item) {
            $this->total += $item->price * $item->quantity;
        }
        // No tax: do not add fixed tax amount
        $transaction->update([
            'customer_name' => $this->customer_name,
            'total' => $this->total,
            'pay' => $this->pay,
            'return' => $this->pay - $this->total,
            'status' => 'Waiting'
        ]);

        return $this->redirectRoute('cashier.success', $transaction->id, navigate: true);
    }
    public function mount()
    {
        $this->produks = Produk::all();
        $this->transaction = Transaction::where('status', 'Cart')->where('cashier_id', Auth::user()->id)->first();
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        if ($category === 'Semua') {
            $this->produks = Produk::all();
        } else {
            $this->produks = Produk::where('category', $category)->get();
        }
    }

    public function render()
    {
        return view('livewire.cashier');
    }
}
