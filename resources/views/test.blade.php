@extends('layouts.app')

@section('content')
    <form action="/products/7622210449283/add-to-shopping-list" method="post">
        @csrf
        <button type="submit">submits</button>
    </form>
{{--    @livewire('account-products-crud')--}}
@endsection
