<?php

namespace App\View\Composers;

use Illuminate\View\View;

class CartComposer
{
    public function compose(View $view): void
    {
        $cart = session()->get('cart', []);
        $cartCount = count($cart);
        $cartTotal = array_sum(
            array_map(fn ($item) => $item['price'] * $item['quantity'], $cart),
        );

        $view->with([
            'cart' => $cart,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal,
        ]);
    }
}
