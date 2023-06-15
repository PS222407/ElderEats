<?php

namespace App\Entities;

class Product
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $brand,
        public ?string $quantityInPackage,
        public string $barcode,
        public ?string $image,
    ) {
    }

    public function getFullName(): string
    {
        return implode(' - ', array_filter([$this->name, $this->brand, $this->quantityInPackage]));
    }
}
