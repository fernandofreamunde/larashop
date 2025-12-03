@extends('layouts.store')

@section('content')
<x-product-list >
    <x-product-list-item
        id="1"
        name="Girl t-shirt"
        description="Fancy white t-shirt for girls that love the summer."
        price="35.99"
        imageUrl="/assets/t1g.jpg"
    />
    <x-product-list-item
        id="2"
        name="Men t-shirt"
        description="Nice black t-shirt for guys that enjoy to exercise."
        price="55.99"
        imageUrl="/assets/t1.jpg"
    />
    <x-product-list-item
        id="3"
        name="Unisex Cap"
        description="For that sunny day in the netherlands summer."
        price="45.55"
        imageUrl="/assets/cap.jpg"
    />
</ x-product-list>
@endsection
