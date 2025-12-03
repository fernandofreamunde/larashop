@extends('layouts.store')

@section('content')
<x-product-list >
    @foreach($products as $product)
    <x-product-list-item
        id="{{ $product->id }}"
        :name="$product->name"
        :description="$product->description"
        price="{{ $product->price }}"
        imageUrl="{{ $product->image_url }}"
    />
    @endforeach
</ x-product-list>
@endsection
