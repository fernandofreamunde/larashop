@props(['name', 'description', 'price', 'imageUrl', 'id'])

<div class="group relative">
  <img src={{ $imageUrl }} alt="Front of men&#039;s Basic Tee in white." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
  <div class="mt-4 flex justify-between">
    <div>
      <h3 class="text-sm text-gray-700">
        <a href={{ "/product/" . $id }}>
          <span aria-hidden="true" class="absolute inset-0"></span>
          Basic Tee
        </a>
      </h3>
      <p class="mt-1 text-sm text-gray-500">{{ $name }}</p>
    </div>
    <p class="text-sm font-medium text-gray-900">€{{ $price }}</p>
  </div>
</div>
