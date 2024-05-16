<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'total' => 'required|numeric',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order = Order::create($validatedData);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'sometimes|required|string|in:pending,processed,completed,cancelled',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->status == 'completed') {
            return response()->json(['error' => 'Cannot update completed order'], 400);
        }

        $order->update($validatedData);

        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->status != 'pending') {
            return response()->json(['error' => 'Cannot delete processed order'], 400);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
