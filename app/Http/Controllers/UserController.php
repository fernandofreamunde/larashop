<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userAttributes = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $customer = Customer::firstOrNew(['email' => $userAttributes['email']]);
        $customer->first_name = $userAttributes['first_name'];
        $customer->last_name = $userAttributes['last_name'];
        unset($userAttributes['last_name']);

        $user = User::create($userAttributes);

        $customer->user_id = $user->id;
        $customer->save();

        Auth::login($user);

        session(['customer_email' => $user->email]);

        return redirect('/');
    }
}
