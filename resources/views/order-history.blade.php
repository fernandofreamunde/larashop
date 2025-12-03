@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="max-w-xl">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Order history</h1>
      <p class="mt-2 text-sm text-gray-500">Check the status of recent orders.</p>
    </div>

    <div class="mt-12">
      @if($orders->isEmpty())
        <div class="text-center py-12">
          <p class="text-gray-500">You have no orders yet.</p>
        </div>
      @else
        <div class="space-y-16">
          @foreach($orders as $order)
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
                  </div>
                  <div class="mt-6 flex space-x-4 sm:mt-0">
                    <a href="{{ route('orders.show', $order->id) }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">View Order</a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
