@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-2xl px-4 pt-16 pb-24 sm:px-6 lg:max-w-7xl lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Checkout</h1>

    <form id="checkout-form" action="{{ route('orders.store') }}" method="POST" class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
      @csrf
      <div class="lg:col-span-7">
        <!-- Contact information -->
        <div>
          <h2 class="text-lg font-medium text-gray-900">Contact information</h2>

          <div class="mt-4">
              <x-forms.input label="Email address" name="email" :value="$customer?->email" :required="true" />
          </div>
        </div>


        <!-- Shipping information -->
        <div class="mt-10 border-t border-gray-200 pt-10">
          <h2 class="text-lg font-medium text-gray-900">Shipping information</h2>

          <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
            <div>
              <x-forms.input label="First name" name="first-name" :value="$customer?->first_name" :required="true" />
            </div>

            <div>
              <x-forms.input label="Last name" name="last-name" :value="$customer?->last_name" :required="true" />
            </div>

            <div class="sm:col-span-2">
                <x-forms.input label="Company" name="company" :value="$customer?->company" :required="true" />
            </div>

            <div class="sm:col-span-2">
                <x-forms.input label="Address" name="address" :required="true" />
            </div>

            <div class="sm:col-span-2">
              <x-forms.input label="Apartment, suite, etc." name="apt-number" />
            </div>

            <div>
              <x-forms.input label="City" name="city" :required="true" />
            </div>

            <div>
                <x-forms.input label="Country" name="country" :required="true" />
            </div>

            <div>
              <x-forms.input label="Postal Code" name="postal-code" :required="true" />
            </div>
          </div>
        </div>

        <!-- Payment -->
        <div class="mt-10 border-t border-gray-200 pt-10">
          <h2 class="text-lg font-medium text-gray-900">Payment</h2>

          <div class="mt-6 grid grid-cols-4 gap-x-4 gap-y-6">
            <div class="col-span-4">
                <x-forms.input label="Card number" name="card-number" />
            </div>

            <div class="col-span-4">
                <x-forms.input label="Name on card" name="name-on-card" />
            </div>

            <div class="col-span-3">
                <x-forms.input label="Expiration date (MM/YY)" name="expiration-date" />
            </div>

            <div>
                <x-forms.input label="CVC" name="cvc" />
            </div>
          </div>
        </div>
      </div>

      <!-- Order summary -->
      <div class="mt-10 lg:col-span-5 lg:mt-0">
        <h2 class="text-lg font-medium text-gray-900">Order summary</h2>

        <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
          <h3 class="sr-only">Items in your cart</h3>
          @if(empty($cart))
            <div class="px-4 py-6 text-center">
              <p class="text-gray-500">Your cart is empty.</p>
            </div>
          @else
            <ul role="list" class="divide-y divide-gray-200">
              @foreach($cart as $productId => $item)
                <li class="flex px-4 py-6 sm:px-6">
                  <div class="shrink-0">
                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-20 rounded-md" />
                  </div>

                  <div class="ml-6 flex flex-1 flex-col">
                    <div class="flex">
                      <div class="min-w-0 flex-1">
                        <h4 class="text-sm">
                          <a href="{{ '/product/' . $productId }}" class="font-medium text-gray-700 hover:text-gray-800">{{ $item['name'] }}</a>
                        </h4>
                      </div>

                      <div class="ml-4 flow-root shrink-0">
                        <button type="submit" form="remove-item-{{ $productId }}" class="-m-2.5 flex items-center justify-center bg-white p-2.5 text-gray-400 hover:text-gray-500">
                          <span class="sr-only">Remove</span>
                          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon" class="size-5">
                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>
                    </div>

                    <div class="flex flex-1 items-end justify-between pt-2">
                      <p class="mt-1 text-sm font-medium text-gray-900">€{{ number_format($item['price'] * $item['quantity'], 2) }}</p>

                      <div class="ml-4">
                        <label for="quantity-{{ $productId }}" class="sr-only">Quantity, {{ $item['name'] }}</label>
                        <select id="quantity-{{ $productId }}" name="quantity" form="update-quantity-{{ $productId }}" onchange="this.form.submit()" class="rounded-md border border-gray-300 text-left text-base font-medium text-gray-700 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden sm:text-sm">
                          <option value="0">0 (Remove)</option>
                          @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                          @endfor
                        </select>
                      </div>
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
          @endif
          <dl class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
              <dt class="text-sm">Subtotal</dt>
              <dd class="text-sm font-medium text-gray-900">€{{ number_format($cartTotal, 2) }}</dd>
            </div>
            <div class="flex items-center justify-between">
              <dt class="text-sm">Shipping</dt>
              <dd class="text-sm font-medium text-gray-900">€{{ number_format(5.00, 2) }}</dd>
            </div>
            <div class="flex items-center justify-between border-t border-gray-200 pt-6">
              <dt class="text-base font-medium">Total</dt>
              <dd class="text-base font-medium text-gray-900">€{{ number_format($cartTotal + 5.00, 2) }}</dd>
            </div>
          </dl>

          <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
            <button type="submit" form="checkout-form" class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-xs hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 focus:outline-hidden">Confirm order</button>
          </div>
        </div>
      </div>
    </form>

    <!-- Cart management forms -->
    @foreach($cart as $productId => $item)
      <form id="remove-item-{{ $productId }}" action="{{ url('/cart/' . $productId) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
      </form>

      <form id="update-quantity-{{ $productId }}" action="{{ url('/cart/' . $productId) }}" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
      </form>
    @endforeach
  </div>
</div>
@endsection
