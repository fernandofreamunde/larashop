<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Store email in session
        session(['customer_email' => $validated['email']]);

        // Find or create customer
        $customer = Customer::where('email', $validated['email'])->first();

        if ($customer) {
            // Update existing customer
            $customer->update([
                'first_name' => $validated['first-name'],
                'last_name' => $validated['last-name'],
                'company' => $validated['company'],
            ]);
        } else {
            // Create new customer
            $customer = Customer::create([
                'email' => $validated['email'],
                'first_name' => $validated['first-name'],
                'last_name' => $validated['last-name'],
                'company' => $validated['company'],
            ]);
        }

        // Get cart from session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        // Calculate total
        $total = array_sum(
            array_map(fn ($item) => $item['price'] * $item['quantity'], $cart),
        );

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'total' => (int) round($total * 100),
            'status' => 'pending',
            'shipping_address' => $validated['address'],
            'shipping_number' => $validated['apt-number'] ?? '',
            'shipping_city' => $validated['city'],
            'shipping_country' => $validated['country'],
            'shipping_postal_code' => $validated['postal-code'],
        ]);

        // Create order details from cart
        foreach ($cart as $productId => $item) {
            OrderDetails::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'current_price' => (int) round($item['price'] * 100),
                'current_name' => $item['name'],
                'current_description' => $item['description'] ?? '',
                'sub_total' => (int) round(
                    $item['price'] * $item['quantity'] * 100,
                ),
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()
            ->route('orders.show', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function index(): View
    {
        $email = session('customer_email');

        if (! $email) {
            return view('order-history', ['orders' => collect()]);
        }

        $customer = Customer::where('email', $email)->first();

        if (! $customer) {
            return view('order-history', ['orders' => collect()]);
        }

        $orders = Order::where('customer_id', $customer->id)
            ->with('orderDetails')
            ->latest()
            ->get();

        return view('order-history', ['orders' => $orders]);
    }

    public function show(Order $order): View
    {
        $email = session('customer_email');

        // Verify the order belongs to the session email
        if (! $email || $order->customer->email !== $email) {
            abort(404);
        }

        $order->load('orderDetails.product', 'customer');

        return view('order-history-detail', ['order' => $order]);
    }
}
