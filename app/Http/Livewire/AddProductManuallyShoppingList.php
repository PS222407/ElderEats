<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddProductManuallyShoppingList extends Component
{
    protected $queryString = [
        'search' => ['except' => ''],
        'productpage' => ['except' => '0'],
    ];

    public $search;

    public $productpage;

    public function nextPage()
    {
        $this->productpage++;
    }

    public function previousPage()
    {
        if ($this->productpage <= 1) {
            $this->productpage = 1;
        }
        $this->productpage--;
    }

    public function searchChanged()
    {
        $this->productpage = 0;
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->offset(4 * $this->productpage)
            ->limit(4)
            ->get();

        return view('livewire.add-product-manually-shopping-list', [
            'products' => $products,
        ]);
    }
}
