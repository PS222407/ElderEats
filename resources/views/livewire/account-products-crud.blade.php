<div class="p-5">
    <table class="mt-10 w-full text-xl">
        <tr class="text-center text-label">
            <th class="px-2">Product</th>
            <th class="px-2">Houdsbaarheidsdatum</th>
        </tr>
        @foreach($products as $product)
            <tr class="border-b-2 @if($loop->first) border-t-2 @endif border-black text-center">
                <td class="flex gap-x-4 items-center p-2">
                    <img src="{{ $product->image ?? asset('Images/No_Image_Available.jpg') }}" alt="product image" class="w-28 aspect-square object-contain">
                    <div>
                        {{ $product->name }} - {{ $product->brand }} - {{ $product->quantity_in_package }}
                    </div>
                </td>
                <td>
                    <div class="flex justify-center gap-x-2 relative">
                        <div class="absolute left-0"
                             x-cloak
                             x-data="{show{{ $product->pivot->id }}: false}"
                             x-show="show{{ $product->pivot->id }}"
                             x-transition.opacity.out.duration.500ms
                             x-init="@this.on('saved-{{ $product->pivot->id }}', () => { show{{ $product->pivot->id }} = true; setTimeout(() => { show{{ $product->pivot->id }} = false }, 1500) })"
                        >
                            <svg fill="lime" width="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>
                        </div>
                        <input type="date" wire:change="update({{ $product->pivot->id }}, $event.target.value)" value="{{ $product->pivot->expiration_date }}">
                    </div>
                    @error('date') <span class="error">{{ $message }}</span> @enderror
                </td>
            </tr>
        @endforeach
    </table>

    {{ $products->links() }}
</div>
