<div class="p-5">
    <div class="flex flex-col">
        <label for="search">Zoeken:</label>
        <input type="search" id="search" name="search" wire:model="search" class="rounded">
    </div>

    <div class="flex justify-center">
        <button onclick="document.dispatchEvent(new CustomEvent('add-product-button-pressed'))"
                class="text-icon text-4xl px-5 py-4 mt-2 shadow border rounded-lg">
            +
        </button>
    </div>

    <table class="mt-5 w-full text-xl">
        <tr class="text-center text-label">
            <th class="px-2">Product</th>
            <th class="px-2">Aantal</th>
            <th class="px-2">Houdbaarheidsdatum</th>
        </tr>
        @foreach($products as $product)
            <tr class="@if($product->pivot->expiration_date == null) bg-gray-300 @elseif($product->pivot->expiration_date < now()) bg-red-300 @elseif($product->pivot->expiration_date < now()->addDays(2)) bg-yellow-300 @endif border-b-2 @if($loop->first) border-t-2 @endif border-black text-center">
                <td class="flex gap-x-4 items-center p-2">
                    <img src="{{ $product->image ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-24 aspect-square object-contain">
                    <div>
                        {{ $product->full_name }}
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

    {{ $products->links() }}
</div>
