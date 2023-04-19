<?php

namespace App\Http\Livewire;

use App\Classes\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class AccountProductsCrud extends Component
{
    use WithPagination;

    public function update($id, $date)
    {
        $validator = Validator::make([
            'date' => $date,
        ], [
            'date' => ['date'],
        ]);

        if ($validator->fails()) return;

        DB::table('account_products')
            ->where('id', $id)
            ->update([
                'expiration_date' => Carbon::create($date),
            ]);

        $this->emit('saved-'.$id);
    }

    public function render()
    {
        $products = Account::$accountModel->activeProducts()->orderBy('expiration_date')->paginate(6);

        return view('livewire.account-products-crud', [
            'products' => $products,
        ]);
    }
}
