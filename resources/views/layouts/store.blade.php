<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite('resources/css/app.css')
    </head>
    <body class="">

        <x-store-navigation />
        @yield('content')

        <!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
        <el-dialog>
          <dialog id="drawer" aria-labelledby="drawer-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">
            <el-dialog-backdrop class="absolute inset-0 bg-gray-500/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>

            <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
              <el-dialog-panel class="ml-auto block size-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                <div class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
                  <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                    <div class="flex items-start justify-between">
                      <h2 id="drawer-title" class="text-lg font-medium text-gray-900">Shopping cart</h2>
                      <div class="ml-3 flex h-7 items-center">
                        <button type="button" command="close" commandfor="drawer" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                          <span class="absolute -inset-0.5"></span>
                          <span class="sr-only">Close panel</span>
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                        </button>
                      </div>
                    </div>

                    <div class="mt-8">
                      <div class="flow-root">
                        @if(empty($cart))
                          <p class="text-center text-gray-500">Your cart is empty.</p>
                        @else
                          <ul role="list" class="-my-6 divide-y divide-gray-200">
                            @foreach($cart as $productId => $item)
                              <li class="flex py-6">
                                <div class="size-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
                                  <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="size-full object-cover" />
                                </div>

                                <div class="ml-4 flex flex-1 flex-col">
                                  <div>
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                      <h3>
                                        <a href="{{ '/product/' . $productId }}">{{ $item['name'] }}</a>
                                      </h3>
                                      <p class="ml-4">€{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                    </div>
                                  </div>
                                  <div class="flex flex-1 items-end justify-between text-sm">
                                    <p class="text-gray-500">Qty {{ $item['quantity'] }}</p>

                                    <div class="flex">
                                      <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                    </div>
                                  </div>
                                </div>
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                      <p>Subtotal</p>
                      <p>€{{ number_format($cartTotal, 2) }}</p>
                    </div>
                    <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                    <div class="mt-6">
                      <a href="/checkout" class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-xs hover:bg-indigo-700">Checkout</a>
                    </div>
                    <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                      <p>
                        or
                        <button type="button" command="close" commandfor="drawer" class="font-medium text-indigo-600 hover:text-indigo-500">
                          Continue Shopping
                          <span aria-hidden="true"> &rarr;</span>
                        </button>
                      </p>
                    </div>
                  </div>
                </div>
              </el-dialog-panel>
            </div>
          </dialog>
        </el-dialog>
    </body>
</html>
