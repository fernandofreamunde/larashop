@props(['label', 'name'])
<div >
    <label for={{ $name }} class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-2">
        <input
            id="{{ $name }}"
            @if($name === "email")
                type="email"
            @elseif($name === "password" || $name === "password_confirmation")
                type="password"
            @else
                type="text"
            @endif
            name="{{ $name }}"
            autocomplete="{{ $name }}"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
        />
    </div>
</div>
