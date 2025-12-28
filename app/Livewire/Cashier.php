<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Midtrans\Snap;
use Midtrans\Config;

class Cashier extends Component
{
    public $produks;
    public $transaction;
    public $selectedCategory = 'Semua';
    public $customer_name;
    public $pay;
    public $total = 0;

    public function mount()
    {
        $this->produks = Produk::all();
        $this->transaction = Transaction::where('status', 'Cart')
            ->where('cashier_id', Auth::user()->id)
            ->first();
    }

    public function render()
    {
        return view('livewire.cashier');
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        $this->produks = $category === 'Semua' 
            ? Produk::all() 
            : Produk::where('category', $category)->get();
    }

    public function addToCart($id)
    {
        if (!Auth::check()) return redirect()->route('login');

        $produk = Produk::find($id);
        $transaction = Transaction::where('status', 'Cart')
            ->where('cashier_id', Auth::user()->id)
            ->first();

        if (!$transaction) {
            $transaction = Transaction::create([
                'customer_name' => 'No Name',
                'cashier_id' => Auth::user()->id,
                'total' => $produk->price,
                'status' => 'Cart',
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'produk_id' => $produk->id,
                'quantity' => 1,
                'price' => $produk->price,
            ]);
        } else {
            $detail = $transaction->details->where('produk_id', $id)->first();
            if ($detail) {
                $detail->update(['quantity' => $detail->quantity + 1]);
            } else {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'produk_id' => $produk->id,
                    'quantity' => 1,
                    'price' => $produk->price,
                ]);
            }
        }

        $this->transaction = Transaction::where('status', 'Cart')
            ->where('cashier_id', Auth::user()->id)
            ->first();
    }

    public function quantity_minus($id)
    {
        $detail = TransactionDetail::find($id);
        if ($detail->quantity == 1) {
            $detail->delete();
        } else {
            $detail->update(['quantity' => $detail->quantity - 1]);
        }

        $this->transaction = Transaction::where('status', 'Cart')
            ->where('cashier_id', Auth::user()->id)
            ->first();
    }

    public function delete($id)
    {
        $detail = TransactionDetail::find($id);
        if ($detail->transaction->details->count() == 1) {
            $detail->transaction->delete();
        } else {
            $detail->delete();
        }

        $this->transaction = Transaction::where('status', 'Cart')
            ->where('cashier_id', Auth::user()->id)
            ->first();
    }

    public function checkout()
    {
        $transaction = $this->transaction;
        $this->total = $transaction->details->sum(fn($d) => $d->price * $d->quantity);

        $transaction->update([
            'customer_name' => $this->customer_name ?? 'No Name',
            'total' => $this->total,
            'pay' => $this->pay,
            'return' => $this->pay - $this->total,
            'status' => 'paid',
            'payment_status' => 'paid',
        ]);

        return $this->redirectRoute('cashier.success', $transaction->id, navigate: true);
    }

    public function nonTunai()
    {
        $transaction = $this->transaction;
        $total = $transaction->details->sum(fn($d) => $d->price * $d->quantity);

        // Update transaksi menjadi pending
        $transaction->update([
            'customer_name' => $this->customer_name ?? 'No Name',
            'total' => $total,
            'payment_method' => 'midtrans',
            'status' => 'pending',
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false; // sandbox
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [ 
            'transaction_details' => [ 
                'order_id' => 'ORDER-' . $transaction->id, 
                'gross_amount' => $total, 
            ], 
            'customer_details' => [ 
                'first_name' => $this->customer_name ?? 'Customer', 
            ], 
        ];

        $snapToken = Snap::getSnapToken($params);

        $this->dispatch( 'pay-midtrans', snapToken: $snapToken );

    }
}