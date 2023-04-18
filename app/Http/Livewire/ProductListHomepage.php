<?php

namespace App\Http\Livewire;

use App\Classes\Account;
use Livewire\Component;

class ProductListHomepage extends Component
{
    protected $listeners = ['livewireRefreshProductListHomepage' => 'render'];

    public $products;

    public function render()
    {
        $this->products = Account::$accountModel->products;

        return view('livewire.product-list-homepage');
    }
}
