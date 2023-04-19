<?php

namespace App\Http\Livewire;

use App\Classes\Account;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListHomepage extends Component
{
    use WithPagination;

    protected $listeners = ['livewireRefreshProductListHomepage' => 'render'];


    public function render()
    {
        DB::statement('SET SESSION sql_mode = ""');
        $products = Account::$accountModel
            ->activeProducts()
            ->select('products.*', 'account_products.id as account_products_id')
            ->selectRaw('COUNT(*) AS account_products_count')
            ->groupBy('expiration_date')
            ->orderByRaw('expiration_date IS NULL ASC, expiration_date ASC')
            ->paginate(6);

        DB::statement('SET SESSION sql_mode = "STRICT_ALL_TABLES"');

        return view('livewire.product-list-homepage', [
            'products' => $products,
        ]);
    }
}
