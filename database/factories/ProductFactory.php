<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'barcode' => fake()->ean13(),
            'brand' => fake()->randomElement(['milka', 'dr. oetker', 'campina', 'sun', 'AH huismerk', 'nutrilon', 'Heiniken', 'katja']),
            'quantity_in_package' => fake()->randomElement([1,2,3,4]),
            'image' => 'https://images.openfoodfacts.org/images/products/301/762/042/2003/front_en.502.400.jpg',
            'created_at' => fake()->dateTimeBetween(now()->subYears(8), now()),
        ];
    }
}
