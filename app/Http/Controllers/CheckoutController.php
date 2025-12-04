<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(public CustomerService $customerService) {}

    public function __invoke(): View
    {
        $customer = $this->customerService->getCustomer();

        $cart = session()->get('cart', []);
        $cartTotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));

        return view('checkout', [
            'customer' => $customer,
            'cart' => $cart,
            'cartTotal' => $cartTotal,
        ]);
    }
}
