@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="max-w-xl">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Order history</h1>
      <p class="mt-2 text-sm text-gray-500">Check the status of recent orders.</p>
    </div>

    <div class="mt-12">
      <div class="space-y-16">
        <!-- Order 1 -->
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
                <a href="/orders/1" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">View Order</a>
              </div>
            </div>

          </div>
        </div>

        <!-- Order 2 -->
        <div class="border-t border-gray-200">
          <div class="rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-4 py-6 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
              <div class="sm:flex sm:space-x-6 lg:space-x-8">
                <div>
                  <div class="text-sm font-medium text-gray-900">Order number</div>
                  <div class="mt-1 text-sm text-gray-500">WU88191112</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Date placed</div>
                  <div class="mt-1 text-sm text-gray-500">Jun 6, 2021</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Total amount</div>
                  <div class="mt-1 text-sm text-gray-500">$40.00</div>
                </div>
              </div>
              <div class="mt-6 flex space-x-4 sm:mt-0">
                <a href="/orders/2" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">View Order</a>
              </div>
            </div>

          </div>
        </div>

        <!-- Order 3 -->
        <div class="border-t border-gray-200">
          <div class="rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-4 py-6 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
              <div class="sm:flex sm:space-x-6 lg:space-x-8">
                <div>
                  <div class="text-sm font-medium text-gray-900">Order number</div>
                  <div class="mt-1 text-sm text-gray-500">WU88191113</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Date placed</div>
                  <div class="mt-1 text-sm text-gray-500">May 24, 2021</div>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">Total amount</div>
                  <div class="mt-1 text-sm text-gray-500">$165.00</div>
                </div>
              </div>
              <div class="mt-6 flex space-x-4 sm:mt-0">
                <a href="/orders/3" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">View Order</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
