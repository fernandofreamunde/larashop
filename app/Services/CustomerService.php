<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    public function getCustomer(): ?Customer
    {
        $customer = null;

        if (Auth::check()) {
            $customer = Customer::where('user_id', Auth::id())->first();
        }

        if (! $customer) {
            $email = session('customer_email');
            if ($email) {
                $customer = Customer::where('email', $email)->first();
            }
        }

        return $customer;
    }
}
