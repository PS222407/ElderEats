{{--<div class="p-5">--}}
{{--    <div class="flex flex-col">--}}
{{--        <label for="search">Zoeken:</label>--}}
{{--        <input type="search" id="search" name="search" wire:model="search" class="rounded">--}}
{{--    </div>--}}

{{--    <div class="flex justify-center">--}}
{{--        <button onclick="document.dispatchEvent(new CustomEvent('add-product-button-pressed'))"--}}
{{--                class="text-icon text-4xl px-5 py-4 mt-2 shadow border rounded-lg">--}}
{{--            +--}}
{{--        </button>--}}
{{--    </div>--}}

{{--    <table class="mt-5 w-full text-xl">--}}
{{--        <tr class="text-center text-label">--}}
{{--            <th class="px-2">Product</th>--}}
{{--            <th class="px-2">Aantal</th>--}}
{{--            <th class="px-2">Houdbaarheidsdatum</th>--}}
{{--        </tr>--}}
{{--        @foreach($productsData as $productData)--}}
{{--            <tr onclick="productDetailModal('detail-product-{{ $productData['product']['id'] }}')" class="@if($productData['accountProduct']['expirationDate'] == null) bg-gray-300 @elseif($productData['accountProduct']['expirationDate'] < now()) bg-red-300 @elseif($productData['accountProduct']['expirationDate'] < now()->addDays(2)) bg-yellow-300 @endif border-b-2 @if($loop->first) border-t-2 @endif cursor-pointer border-black text-center">--}}
{{--                <td class="flex gap-x-4 items-center p-2">--}}
{{--                    <img src="{{ $productData['product']['image'] ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-24 aspect-square object-contain">--}}
{{--                    <div>--}}
{{--                        {{ $productData['product']['fullName'] }}--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{ $productData['count'] }}x--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{ dateStringToHumanNL($productData['accountProduct']['expirationDate']) }}--}}
{{--                </td>--}}
{{--            </tr>--}}

{{--            <dialog id="detail-product-{{ $productData['product']['id'] }}" class="p-0 rounded w-[600px]">--}}
{{--                <!-- Modal header -->--}}
{{--                <div class="flex items-center justify-between p-5 bg-banner border-b rounded-t">--}}
{{--                    <h3 class="text-xl font-semibold text-white lg:text-2xl">--}}
{{--                        Product:--}}
{{--                    </h3>--}}
{{--                    <button onclick="document.getElementById('detail-product-{{ $productData['product']['id'] }}').close()" type="button" class="text-gray-400 bg-transparent hover:shadow-lg rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">--}}
{{--                        <svg class="w-10 h-10" fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <!-- Modal body -->--}}
{{--                <div class="p-6 space-y-6">--}}
{{--                    <div class="flex flex-col gap-y-2">--}}
{{--                        <div class="flex flex-col gap-x-4 items-center p-2">--}}
{{--                            <img src="{{ $productData['product']['image'] ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-64 aspect-square object-contain">--}}
{{--                            <div class="flex flex-col w-fit items-center">--}}
{{--                                <div class="w-fit font-bold">--}}
{{--                                    {{ $productData['product']['fullName'] }}--}}
{{--                                </div>--}}
{{--                                <div class="w-fit">--}}
{{--                                    {{ $productData['count'] }}x--}}
{{--                                </div>--}}
{{--                                <div class="w-fit">--}}
{{--                                    {{ dateStringToHumanNL($productData['accountProduct']['expirationDate']) }}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <form action="{{ route('products.pivotId.detach', $productData['accountProduct']['id']) }}" method="POST">--}}
{{--                        @csrf--}}
{{--                        @method('DELETE')--}}
{{--                        <button type="submit" class="mx-auto block button-delete">--}}
{{--                            Verwijderen--}}
{{--                        </button>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <!-- Modal footer -->--}}
{{--                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">--}}
{{--                    <button onclick="document.getElementById('detail-product-{{ $productData['product']['id'] }}').close()" type="button" class="text-gray-500 font-bold rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">--}}
{{--                        Sluiten--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </dialog>--}}
{{--        @endforeach--}}
{{--    </table>--}}

{{--    @dd($paginateData)--}}
{{--    @if($paginateData)--}}
{{--        <div class="w-full relative mt-2">--}}
{{--            @if($paginateData['previousPageUrl'])--}}
{{--                <button wire:click="previousPage" class="block left-0 absolute py-2 px-3 rounded border">Vorige</button>--}}
{{--            @endif--}}
{{--            @if($paginateData['nextPageUrl'])--}}
{{--                <button wire:click="nextPage" class="block right-0 absolute py-2 px-3 rounded border">Volgende</button>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}

{{--        @dd($paginateData)--}}
{{--    {{ $products->links() }}--}}
{{--</div>--}}

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
        <button onclick="document.dispatchEvent(new CustomEvent('add-product-shoppinglist-button-pressed'))"
        class="absolute right-5 text-icon text-4xl px-4 py-3 mt-2 shadow border rounded-lg">
        <img class="w-9 h-12" src="{{ asset('Images/shopping-cart-outline-svgrepo-com.svg') }}" alt="boodschappen lijst" />
</button>
    </div>


    <table class="mt-5 w-full text-xl">
        <tr class="text-center text-label">
            <th class="px-2">Product</th>
            <th class="px-2">Aantal</th>
            <th class="px-2">Houdbaarheidsdatum</th>
        </tr>
        @foreach($products as $product)
{{--            @dd($product->toArray())--}}

{{--            @dd($product->toArray(), $product->pivot->expiration_date)--}}
            <tr onclick="productDetailModal('detail-product-{{ $product->id }}')" class="@if($product->pivot->expiration_date == null) bg-gray-300 @elseif($product->pivot->expiration_date < now()) bg-red-300 @elseif($product->pivot->expiration_date < now()->addDays(2)) bg-yellow-300 @endif border-b-2 @if($loop->first) border-t-2 @endif cursor-pointer border-black text-center">
                <td class="flex gap-x-4 items-center p-2">
                    <img src="{{ $product->image ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-24 aspect-square object-contain">
                    <div>
                        {{ $product->full_name }}
                    </div>
                </td>
                <td>
                    {{ $product['account_products_count'] }}x
                </td>
                <td>
                    {{ dateStringToHumanNL($product->pivot->expiration_date) }}
                </td>
            </tr>

            <dialog id="detail-product-{{ $product['id'] }}" class="p-0 rounded w-[600px]">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 bg-banner border-b rounded-t">
                    <h3 class="text-xl font-semibold text-white lg:text-2xl">
                        Product:
                    </h3>
                    <button onclick="document.getElementById('detail-product-{{ $product['id'] }}').close()" type="button" class="text-gray-400 bg-transparent hover:shadow-lg rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-10 h-10" fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="flex flex-col gap-y-2">
                        <div class="flex flex-col gap-x-4 items-center p-2">
                            <img src="{{ $product['image'] ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-64 aspect-square object-contain">
                            <div class="flex flex-col w-fit items-center">
                                <div class="w-fit font-bold">
                                    {{ $product['fullName'] }}
                                </div>
                                <div class="w-fit">
                                    {{ $product['account_products_count'] }}x
                                </div>
                                <div class="w-fit">
                                    {{ dateStringToHumanNL($product->pivot->expirationDate) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('products.pivotId.detach', $product['pivot']['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mx-auto block button-delete">
                            Verwijderen
                        </button>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    <button onclick="document.getElementById('detail-product-{{ $product['id'] }}').close()" type="button" class="text-gray-500 font-bold rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Sluiten
                    </button>
                </div>
            </dialog>
        @endforeach
    </table>

    {{--    @dd($paginateData)--}}
    @if($paginateData)
        <div class="w-full relative mt-2">
            @if($paginateData['previousPageUrl'])
                <button wire:click="previousPage" class="block left-0 absolute py-2 px-3 rounded border">Vorige</button>
            @endif
            @if($paginateData['nextPageUrl'])
                <button wire:click="nextPage" class="block right-0 absolute py-2 px-3 rounded border">Volgende</button>
            @endif
        </div>
    @endif

    {{ $products->links() }}
</div>
