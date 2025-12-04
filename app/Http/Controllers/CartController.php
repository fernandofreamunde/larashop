<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $productId = $validated['product_id'];
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $validated['quantity'];
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'description' => $product->description,
                'quantity' => $validated['quantity'],
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(
        Request $request,
        string $productId,
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($validated['quantity'] == 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $validated['quantity'];
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function destroy(string $productId): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }
}
