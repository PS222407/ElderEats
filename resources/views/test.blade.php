@extends('layouts.app')

@section('content')
    @foreach($products as $product)
        <div>
            <div class="text-xl font-bold">{{ $product->name }} - {{ $product->brand }} - {{ $product->quantity_in_package }}</div>
            <div>
                <img src="{{ $product->image }}" alt="product image" class="w-28">
            </div>
        </div>
    @endforeach
@endsection
