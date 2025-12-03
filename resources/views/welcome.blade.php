@extends('layouts.store')

@section('content')
<x-product-list>
    @foreach($products as $product)
        <x-product-list-item :$product />
    @endforeach
</x-product-list>
@endsection
