<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        session(["customer_email" => $validated["email"]]);

        $customer = Customer::where("email", $validated["email"])->first();

        if ($customer) {
            $customer->update([
                "first_name" => $validated["first-name"],
                "last_name" => $validated["last-name"],
                "company" => $validated["company"],
            ]);
        } else {
            $customer = Customer::create([
                "email" => $validated["email"],
                "first_name" => $validated["first-name"],
                "last_name" => $validated["last-name"],
                "company" => $validated["company"],
            ]);
        }

        $cart = session()->get("cart", []);

        if (empty($cart)) {
            return redirect()->back()->with("error", "Your cart is empty");
        }

        $order = $this->createOrderFromCart($cart, $customer, $validated);

        session()->forget("cart");

        return redirect()
            ->route("orders.show", $order->id)
            ->with("success", "Order placed successfully!");
    }

    public function index(): View
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return view("order-history", ["orders" => collect()]);
        }

        $orders = Order::where("customer_id", $customer->id)
            ->with("orderDetails")
            ->latest()
            ->get();

        return view("order-history", ["orders" => $orders]);
    }

    public function show(Order $order): View
    {
        $customer = $this->getCustomer();

        // Verify the order belongs to the customer
        if (!$customer || $order->customer_id !== $customer->id) {
            abort(404);
        }

        $order->load("orderDetails.product", "customer");

        return view("order-history-detail", ["order" => $order]);
    }

    private function getCustomer()
    {
        $customer = null;

        if (Auth::check()) {
            $customer = Customer::where("user_id", Auth::id())->first();
        }

        if (!$customer) {
            $email = session("customer_email");
            if ($email) {
                $customer = Customer::where("email", $email)->first();
            }
        }

        return $customer;
    }

    private function createOrderFromCart(
        array $cart,
        Customer $customer,
        array $shipping,
    ): Order {
        $total = array_sum(
            array_map(fn($item) => $item["price"] * $item["quantity"], $cart),
        );

        $order = Order::create([
            "customer_id" => $customer->id,
            "total" => (int) round($total * 100),
            "status" => "pending",
            "shipping_address" => $shipping["address"],
            "shipping_number" => $shipping["apt-number"] ?? "",
            "shipping_city" => $shipping["city"],
            "shipping_country" => $shipping["country"],
            "shipping_postal_code" => $shipping["postal-code"],
        ]);

        // Create order details from cart
        foreach ($cart as $productId => $item) {
            OrderDetails::create([
                "order_id" => $order->id,
                "product_id" => $productId,
                "quantity" => $item["quantity"],
                "current_price" => (int) round($item["price"] * 100),
                "current_name" => $item["name"],
                "current_description" => $item["description"] ?? "",
                "sub_total" => (int) round(
                    $item["price"] * $item["quantity"] * 100,
                ),
            ]);
        }

        return $order;
    }
}
