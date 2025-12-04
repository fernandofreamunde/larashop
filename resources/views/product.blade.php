@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
      <!-- Image -->
      <div class="aspect-square w-full overflow-hidden rounded-lg">
        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
            class="size-full object-cover object-center"
        />
      </div>

      <!-- Product info -->
      <div class="mt-10 lg:mt-0">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

        <div class="mt-3">
          <p class="text-3xl tracking-tight text-gray-900">€{{ $product->price }}</p>
        </div>

        <div class="mt-6">
          <h3 class="sr-only">Description</h3>
          <div class="space-y-4">
            @foreach(explode("\n", $product->description) as $paragraph)
              @if(trim($paragraph))
                <p class="text-base text-gray-700">{{ $paragraph }}</p>
              @endif
            @endforeach
          </div>
        </div>

        <div class="mt-6">
          <form action="{{ url('/cart') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button
                type="submit"
                class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">
                    Add to bag
                </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
