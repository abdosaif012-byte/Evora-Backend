<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;

class CartController extends Controller
{

    public function index(Request $request){
        $cartItems = CartItem::where('user_id', $request->user()->id)->with('product')->get();

        return response()->json($cartItems);
    }


    public function store(Request $request){
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $user = $request->user();

        // check if the product is already in the cart
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {

            $cartItem->quantity += $request->quantity;
            $cartItem->save();

        } else {

            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);

        }
        return response()->json([
            'message' => 'Product added to cart',
            'cart_item' => $cartItem
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cart updated',
            'cart_item' => $cartItem
        ]);
    }


    // Remove product from cart
    public function destroy(Request $request, $id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'message' => 'Product removed from cart'
        ]);
    }
}
