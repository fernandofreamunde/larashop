@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="max-w-xl">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Order #1234567</h1>
      <p class="mt-2 text-sm text-gray-500">Check the status of recent orders, manage returns, and discover similar products.</p>
    </div>

    <div class="mt-12">
      <div class="space-y-16">
        <div class="border-t border-gray-200">
          <div class="rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-4 py-6 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
              <div class="sm:flex sm:space-x-6 lg:space-x-8">
                <div>
                  <div class="text-sm font-medium text-gray-900">Order number</div>
                  <div class="mt-1 text-sm text-gray-500">WU88191111</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Date placed</div>
                  <div class="mt-1 text-sm text-gray-500">Jul 6, 2021</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Total amount</div>
                  <div class="mt-1 text-sm text-gray-500">$160.00</div>
                </div>
              </div>
              <div class="mt-6 flex space-x-4 sm:mt-0">
              </div>
            </div>

            <!-- Products -->
            <div class="divide-y divide-gray-200">
              <!-- Product 1 -->
              <div class="px-4 py-6 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-8 lg:py-8">
                <div class="sm:flex lg:col-span-7">
                  <div class="aspect-square w-24 shrink-0 overflow-hidden rounded-lg sm:w-32 lg:w-40">
                    <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/order-history-page-03-product-01.jpg" alt="Olive drab green insulated bottle with flared screw lid and flat top." class="size-full object-cover object-center">
                  </div>

                  <div class="mt-6 sm:ml-6 sm:mt-0 lg:flex-1">
                    <div>
                      <div class="flex justify-between">
                        <h3 class="text-base font-medium text-gray-900">Micro Backpack</h3>
                        <p class="ml-4 text-base font-medium text-gray-900">$70.00</p>
                      </div>
                      <p class="mt-2 text-sm text-gray-500">Are you a minimalist looking for a compact carry option? The Micro Backpack is the perfect size for your essential everyday carry items. Wear it like a backpack or carry it like a satchel for all-day use.</p>
                    </div>
                  </div>
                </div>

                <div class="mt-6 lg:col-span-5 lg:mt-0">
                  <dl class="grid grid-cols-2 gap-x-6 text-sm">
                    <div>
                      <dt class="flex items-center text-gray-900">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mr-2 size-5 text-green-500">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        Delivered on July 12, 2021
                      </dt>
                    </div>
                    <div class="flex justify-end space-x-4 text-indigo-600">
                      <a href="#" class="font-medium hover:text-indigo-500">View product</a>
                      <span class="text-gray-300">|</span>
                      <a href="#" class="font-medium hover:text-indigo-500">Buy again</a>
                    </div>
                  </dl>
                </div>
              </div>

              <!-- Product 2 -->
              <div class="px-4 py-6 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-8 lg:py-8">
                <div class="sm:flex lg:col-span-7">
                  <div class="aspect-square w-24 shrink-0 overflow-hidden rounded-lg sm:w-32 lg:w-40">
                    <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/order-history-page-03-product-02.jpg" alt="Yellow shopping tote with handle." class="size-full object-cover object-center">
                  </div>

                  <div class="mt-6 sm:ml-6 sm:mt-0 lg:flex-1">
                    <div>
                      <div class="flex justify-between">
                        <h3 class="text-base font-medium text-gray-900">Nomad Shopping Tote</h3>
                        <p class="ml-4 text-base font-medium text-gray-900">$90.00</p>
                      </div>
                      <p class="mt-2 text-sm text-gray-500">This durable shopping tote is perfect for the world traveler. Its yellow canvas construction is water, fray, tear resistant. The matching handle, backpack straps, and shoulder loops provide multiple carry options for a day out on your next adventure.</p>
                    </div>
                  </div>
                </div>

                <div class="mt-6 lg:col-span-5 lg:mt-0">
                  <dl class="grid grid-cols-2 gap-x-6 text-sm">
                    <div>
                      <dt class="flex items-center text-gray-900">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mr-2 size-5 text-green-500">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        Delivered on July 12, 2021
                      </dt>
                    </div>
                    <div class="flex justify-end space-x-4 text-indigo-600">
                      <a href="#" class="font-medium hover:text-indigo-500">View product</a>
                      <span class="text-gray-300">|</span>
                      <a href="#" class="font-medium hover:text-indigo-500">Buy again</a>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
