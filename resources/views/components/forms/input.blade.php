@props(['label', 'name', 'value' => '', 'required' => false])
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
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
            value="{{ old($name, $value) }}"
            autocomplete="{{ $name }}"
            @if($required) required @endif
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 {{ $errors->has($name) ? 'outline-red-300 focus:outline-red-600' : 'outline-gray-300 focus:outline-indigo-600' }} placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 sm:text-sm/6"
        />
    </div>
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
