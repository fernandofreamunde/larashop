@extends('layouts.store')

@section('content')
<div class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
      <!-- Image -->
      <div class="aspect-square w-full overflow-hidden rounded-lg">
        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Two each of gray, white, and black shirts laying flat." class="size-full object-cover object-center" />
      </div>

      <!-- Product info -->
      <div class="mt-10 lg:mt-0">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Basic Tee 6-Pack</h1>

        <div class="mt-3">
          <p class="text-3xl tracking-tight text-gray-900">$192</p>
        </div>

        <div class="mt-6">
          <h3 class="sr-only">Description</h3>
          <p class="text-base text-gray-700">The Basic Tee 6-Pack allows you to fully express your vibrant personality with three grayscale options. Feeling adventurous? Put on a heather gray tee. Want to be a trendsetter? Try our exclusive colorway: "Black". Need to add an extra pop of color to your outfit? Our white tee has you covered.</p>
        </div>

        <div class="mt-6">
          <form>
            <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">Add to bag</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
