<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        // Inisialisasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false; // sandbox
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Tangkap notification
        $notification = new Notification();

        $orderId = $notification->order_id; // misal "ORDER-123"
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        // Cari transaksi di database berdasarkan order_id
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Update status transaksi berdasarkan notification Midtrans
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    $transaction->update([
                        'status' => 'pending',
                        'payment_status' => 'challenge'
                    ]);
                } else {
                    $transaction->update([
                        'status' => 'paid',
                        'payment_status' => 'settlement'
                    ]);
                }
                break;

            case 'settlement':
                $transaction->update([
                    'status' => 'paid',
                    'payment_status' => 'settlement'
                ]);
                break;

            case 'pending':
                $transaction->update(['payment_status' => 'pending']);
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $transaction->update([
                    'status' => 'cancel',
                    'payment_status' => $transactionStatus
                ]);
                break;
        }

        // Kembalikan response 200 ke Midtrans
        return response()->json(['ok']);
    }
}