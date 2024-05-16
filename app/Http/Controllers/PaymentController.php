<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
       $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $order = Order::find($request->order_id);

        if ($order->status != 'pending') {
            return response()->json(['error' => 'Cannot make a payment for processed order'], 400);
        }

        if ($order->total != $request->amount) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $request->amount;
        $payment->status = 'completed'; // For simplicity, assume payment is always successful
        $payment->save();

        $order->status = 'processed';
        $order->save();

        return response()->json(['message' => 'Payment processed successfully', 'payment' => $payment], 200);
    }
}
