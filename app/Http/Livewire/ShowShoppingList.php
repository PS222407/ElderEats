<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Classes\Account;

class ShowShoppingList extends Component
{
    public function render()
    {
        $products =  \App\Models\Account::find(Account::$accountEntity->id)->shoppingListWithoutTimestamps()->get();

        //dd($products);

        return view('livewire.show-shopping-list', [
            'products' => $products,
            'index' => 0,
        ]);
    }
}
