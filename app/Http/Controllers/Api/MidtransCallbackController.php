<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;

/**
 * Modul: API Payment Gateway
 * Fitur: Menangani callback/notifikasi pembayaran dari Midtrans.
 */
class MidtransCallbackController extends Controller
{
    /**
     * Bagian: Inisialisasi konfigurasi Midtrans SDK.
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Bagian: Endpoint callback Midtrans.
     * Alur: validasi signature -> cari transaksi -> sinkronkan status transaksi.
     */
    public function handle(Request $request)
    {
        try {
            $notification = new Notification;

            $transactionStatus = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $serverKey = config('midtrans.server_key');

            // Verifikasi Signature Key
            $signatureKey = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
            if ($notification->signature_key != $signatureKey) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Signature key tidak valid',
                ], 403);
            }

            $transaksi = Transaksi::where('order_id', $orderId)->first();

            if (! $transaksi) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan',
                ], 404);
            }

            DB::transaction(function () use ($transactionStatus, $fraudStatus, $transaksi) {
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $transaksi->markAsPaid();
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $transaksi->markAsPaid();
                } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $transaksi->markAsCancelled();
                } elseif ($transactionStatus == 'pending') {
                    $transaksi->update(['status' => 'Menunggu Pembayaran']);
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Callback diproses',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
