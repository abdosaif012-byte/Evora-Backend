<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;

class OrderController extends Controller
{
    // Get user's orders
    public function userOrders(Request $request)
    {
        $orders = Order::with('items.product')
        ->where('user_id', $request->user()->id)
        ->get();

    
        return response()->json($orders);
    }

    // get all users orders (only for admins)
    public function allOrders(Request $request)
    {
        if ($request->user()->role !== 'admin') { return response()->json([ 'message' => 'Unauthorized , need admin role' ], 403); }

    $orders = Order::with('user', 'items.product')->get();

    return response()->json($orders);
    }


    // Create order from cart
    public function store(Request $request)
    {
        $user = $request->user();

        // Get user's cart
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        // Calculate total
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($cartItems as $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        // Empty cart after confirm the order and store it 
        CartItem::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }


    // Show one order
    public function show(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') { return response()->json([ 'message' => 'Unauthorized , need admin role' ], 403); }
        $order = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->findOrFail($id);

        return response()->json($order);
    }


    // Update order status
    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') { return response()->json([ 'message' => 'Unauthorized , need admin role to update' ], 403); }
        $order = Order::findOrFail($id);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order updated',
            'order' => $order
        ]);
    }


    // Delete order
    public function destroy(Request $request,$id)
    {
        if ($request->user()->role !== 'admin') { return response()->json([ 'message' => 'Unauthorized , need admin role to delete' ], 403); }
        $order = Order::findOrFail($id);

        $order->delete();

        return response()->json([
            'message' => 'Order deleted'
        ]);
    }
}