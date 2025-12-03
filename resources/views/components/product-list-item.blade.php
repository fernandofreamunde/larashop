@props(['product'])

<div class="group relative">
  <img
      src="{{ $product->image_url }}"
      alt="{{ $product->name }}"
      class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80"
  />

  <div class="mt-4 flex justify-between">
    <div>
      <h3 class="text-sm text-gray-700">
        <a href="{{ '/product/' . $product->id }}">
          <span aria-hidden="true" class="absolute inset-0"></span>
          {{ $product->name }}
        </a>
      </h3>
      <p class="mt-1 text-sm text-gray-500">{{ Str::before($product->description, "\n") }}</p>
    </div>
    <p class="text-sm font-medium text-gray-900">€{{ $product->price }}</p>
  </div>
</div>
