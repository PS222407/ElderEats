<?php

namespace App\Http\Livewire;

use App\Classes\Account;
use App\Classes\ApiEndpoint;
use App\Entities\AccountProduct;
use App\Entities\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListHomepage extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];

    protected $listeners = ['livewireRefreshProductListHomepage' => 'render'];

    public $search;

    public $paginateData;

    public $productsData;

    public function mount()
    {
        $this->productsData = $this->loadProducts();
    }

    public function nextPage()
    {
        $this->loadProducts();
    }

    public function previousPage()
    {
        $this->loadProducts();
    }

    private function loadProducts(int $page = null)
    {
        $response = Http::withoutVerifying()->withHeaders([
            'x-api-key' => Account::$accountEntity->token,
        ])->withUrlParameters([
            'name' => $this->search ?? ' ',
        ])->get(config('app.api_base_url') . ApiEndpoint::SEARCH_PRODUCTS_FROM_ACCOUNT, [
            'take' => 4,
            'page' => $page ?? 1,
        ]);

        $this->paginateData = $response?->json()['paginate'];

        $productsData = [];
        foreach ($response->json()['data'] as $data) {
//            $product = new Product(
//                $data['product']['id'],
//                $data['product']['name'],
//                $data['product']['brand'] ?? null,
//                $data['product']['quantityInPackage'] ?? null,
//                $data['product']['barcode'],
//                $data['product']['image'] ?? null,
//            );
//            $accountProduct = new AccountProduct(
//                $data['accountProduct']['id'],
//                $data['accountProduct']['expirationDate'],
//                $data['accountProduct']['ranOutAt'],
//                $data['accountProduct']['createdAt'],
//                $data['accountProduct']['updatedAt'],
//            );
            $productsData[] = [
                'accountProduct' => $accountProduct,
                'product' => $product,
                'count' => $data['count']
            ];
        }

        return $productsData;
    }

    public function render()
    {
        //        DB::statement('SET SESSION sql_mode = ""');
        //        $products = Account::$accountEntity
        //            ->activeProducts()
        //            ->selectRaw('COUNT(*) AS account_products_count')
        //            ->where('name', 'like', '%' . $this->search . '%')
        //            ->groupBy('id', 'expiration_date')
        //            ->orderByRaw('expiration_date IS NULL ASC, expiration_date ASC')
        //            ->paginate(4);
        //        DB::statement('SET SESSION sql_mode = "STRICT_ALL_TABLES"');

        return view('livewire.product-list-homepage');
    }
}
