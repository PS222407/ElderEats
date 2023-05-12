<?php

namespace App\Http\Requests;

use App\Rules\Barcode;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'ean' => ['required', new Barcode()],
            'amount' => ['required', 'integer'],
        ];
    }
}
