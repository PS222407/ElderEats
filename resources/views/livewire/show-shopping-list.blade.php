<div class="">
   
    @foreach($products as $product)
    <tr class="">
        <td>

            @if($index % 2 == 1)
            <div class=" bg-gray-50">
            @else
            <div class=" bg-gray-100">
            @endif
            
            {{$product->name}}
            @if(!is_null($product->brand))
            - {{$product->brand}}
            @endif
            </div>
            <?php
                $index++
            ?>
        </td>
    </tr>
@endforeach
</div>
