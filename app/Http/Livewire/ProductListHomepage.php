<?php

namespace App\Http\Livewire;

use App\Classes\Account;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductListHomepage extends Component
{
    protected $listeners = ['livewireRefreshProductListHomepage' => 'render'];

    public $products;

    public function render()
    {
        DB::statement('SET SESSION sql_mode = ""');
        $this->products = Account::$accountModel
            ->activeProducts()
            ->selectRaw('*, COUNT(*) AS account_products_count')
            ->groupBy('expiration_date')
            ->get();
        DB::statement('SET SESSION sql_mode = "STRICT_ALL_TABLES"');

        return view('livewire.product-list-homepage');
    }
}
