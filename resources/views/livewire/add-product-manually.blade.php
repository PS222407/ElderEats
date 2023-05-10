<div>
    <div class="flex flex-col">
        <label for="search-products">Zoeken:</label>
        <input type="search" id="search-products" name="search" wire:model="search" wire:keydown="searchChanged" class="rounded">
    </div>

    <div class="flex justify-center">
        <button onclick="document.dispatchEvent(new CustomEvent('add-non-existing-product-button-pressed'))"
                class="text-icon text-4xl px-5 py-4 mt-2 shadow border rounded-lg">
            +
        </button>
    </div>

    <table class="mt-5 w-full text-xl">
        <tr class="text-center text-label">
            <th class="px-2">Product</th>
        </tr>
        @foreach($products as $product)
            <tr class="border-b-2 @if($loop->first) border-t-2 @endif border-black text-center">
                <td>
                    <form action="{{ route('product.add-manual-existing-product', ['id' => $product->id]) }}" method="POST">
                        @csrf
                        <button class="flex w-full gap-x-4 items-center p-2 hover:bg-gray-100">
                            <img src="{{ $product->image ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-24 aspect-square object-contain">
                            <div>
                                {{ $product->name }}
                            </div>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    <div class="flex justify-between">
        <button wire:click="previousPage" class="px-3 py-1 border shadow rounded mt-2 hover:bg-gray-50">Vorige</button>
        <button wire:click="nextPage" class="px-3 py-1 border shadow rounded mt-2 hover:bg-gray-50">Volgende</button>
    </div>
</div>
