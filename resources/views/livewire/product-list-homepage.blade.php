<div class="p-5">
    <table class="mt-10 w-full text-xl">
        <tr class="text-center text-label">
            <th class="px-2">Product</th>
            <th class="px-2">Aantal</th>
            <th class="px-2">Houdsbaarheidsdatum</th>
        </tr>
        @foreach($products as $product)
            <tr class="border-b-2 @if($loop->first) border-t-2 @endif border-black text-center">
                <td class="flex items-center p-2">
                    <img src="{{ $product->image ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-28 aspect-square object-contain">
                    <div>
                        {{ $product->name }} - {{ $product->brand }} - {{ $product->quantity_in_package }}
                    </div>
                </td>
                <td>
                    {{ $product->account_products_count }}x
                </td>
                <td>
                    {{ dateStringToHumanNL($product->pivot->expiration_date) }}
                </td>
            </tr>
        @endforeach
    </table>
</div>
