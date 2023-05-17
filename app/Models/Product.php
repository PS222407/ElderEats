<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'quantity_in_package',
        'barcode',
        'image',
    ];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(' - ', array_filter([$this->name, $this->brand, $this->quantity_in_package])),
        );
    }
}
