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

        $accountId = Account::$accountModel->id;

        $sql = "SELECT
                products.*,
                count(*) as account_products_count,
                account_products.account_id AS pivot_account_id,
                account_products.product_id AS pivot_product_id,
                account_products.id AS pivot_id,
                account_products.expiration_date AS pivot_expiration_date,
                account_products.ran_out_at AS pivot_ran_out_at,
                account_products.created_at AS pivot_created_at,
                account_products.updated_at AS pivot_updated_at
                FROM products
                INNER JOIN account_products ON products.id = account_products.product_id
                WHERE account_products.account_id IN (:accountId)
                AND (account_products.ran_out_at > NOW() OR account_products.ran_out_at IS NULL)
                GROUP BY account_products.product_id, account_products.expiration_date;";

        DB::statement('SET SESSION sql_mode = "STRICT_ALL_TABLES"');

        $this->products = DB::select($sql, ['accountId' => $accountId]);

        return view('livewire.product-list-homepage');
    }
}
