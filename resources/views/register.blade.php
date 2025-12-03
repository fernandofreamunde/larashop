@extends('layouts.store')

@section('content')
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img src="/assets/logo.png" alt="{{ config('app.name') }}" class="mx-auto h-10 w-auto" />
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Create your account</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form action="{{ route('register') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <x-forms.input label="First name" name="first_name" />
        </div>

        <div>
            <x-forms.input label="Last name" name="last_name" />
        </div>

        <div>
            <x-forms.input label="Email address" name="email" />
        </div>

        <div>
                <x-forms.input label="Password" name="password" />
        </div>

        <div>
              <x-forms.input label="Confirm password" name="password_confirmation" />
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Register
          </button>
        </div>
      </form>

    <p class="mt-10 text-center text-sm/6 text-gray-500">
        Have an Account?
      <a href="/login" class="font-semibold text-indigo-600 hover:text-indigo-500">Login</a>
    </p>
  </div>
</div>
@endsection
