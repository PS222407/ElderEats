<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Classes\Account;

class ShowShoppingList extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        $products =  \App\Models\Account::find(Account::$accountEntity->id)->shoppingListWithoutTimestamps()->get();

        return view('livewire.show-shopping-list', [
            'products' => $products,
            'index' => 0,
        ]);
    }
}
