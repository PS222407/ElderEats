<?php

namespace App\Entities;

class Product
{
    public string $fullName;

    public function __construct(
        public int $id,
        public string $name,
        public ?string $brand,
        public ?string $quantityInPackage,
        public ?string $barcode,
        public ?string $image,
    ) {
        $this->fullName = $this->getFullName();
    }

    public function getFullName(): string
    {
        return implode(' - ', array_filter([$this->name, $this->brand, $this->quantityInPackage]));
    }
}
