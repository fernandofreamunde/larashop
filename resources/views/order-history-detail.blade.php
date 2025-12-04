@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="max-w-xl">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Order #{{ $order->id }}</h1>
      <p class="mt-2 text-sm text-gray-500">Order placed on {{ $order->created_at->format('F j, Y') }}</p>
    </div>

    <div class="mt-12">
      <div class="space-y-16">
        <div class="border-t border-gray-200">
          <div class="rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-4 py-6 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
              <div class="sm:flex sm:space-x-6 lg:space-x-8">
                <div>
                  <div class="text-sm font-medium text-gray-900">Order number</div>
                  <div class="mt-1 text-sm text-gray-500">#{{ $order->id }}</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Date placed</div>
                  <div class="mt-1 text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Total amount</div>
                  <div class="mt-1 text-sm text-gray-500">€{{ number_format($order->total / 100, 2) }}</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Status</div>
                  <div class="mt-1">
                    <x-order-status-badge :status="$order->status" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Products -->
            <div class="divide-y divide-gray-200">
              @foreach($order->orderDetails as $detail)
                <div class="px-4 py-6 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-8 lg:py-8">
                  <div class="sm:flex lg:col-span-7">
                    <div class="aspect-square w-24 shrink-0 overflow-hidden rounded-lg sm:w-32 lg:w-40">
                      <img src="{{ $detail->product->image_url }}" alt="{{ $detail->current_name }}" class="size-full object-cover object-center">
                    </div>

                    <div class="mt-6 sm:ml-6 sm:mt-0 lg:flex-1">
                      <div>
                        <div class="flex justify-between">
                          <h3 class="text-base font-medium text-gray-900">{{ $detail->current_name }}</h3>
                          <p class="ml-4 text-base font-medium text-gray-900">€{{ number_format($detail->sub_total / 100, 2) }}</p>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">{{ Str::before($detail->current_description, "\n") }}</p>
                        <p class="mt-2 text-sm text-gray-700">Quantity: {{ $detail->quantity }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="mt-6 lg:col-span-5 lg:mt-0">
                    <dl class="grid grid-cols-1 gap-x-6 text-sm">
                      <div class="flex justify-end space-x-4 text-indigo-600">
                        <a href="{{ url('/product/' . $detail->product_id) }}" class="font-medium hover:text-indigo-500">View product</a>
                      </div>
                    </dl>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
