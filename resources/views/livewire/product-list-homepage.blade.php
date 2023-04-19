<div>
    @foreach($products as $product)
        <div>
            <div class="text-xl font-bold">{{ $product->name }} - {{ $product->brand }} - {{ $product->quantity_in_package }} - {{ $product->barcode }} | {{ dateStringToHumanNL($product->pivot_expiration_date) }} {{ $product->account_products_count }}x</div>
            <div>
                <img src="{{ $product->image ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-28 aspect-square object-contain">
            </div>
        </div>
    @endforeach
</div>
