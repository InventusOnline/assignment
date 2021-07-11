<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => 'simple',
            'catalog_visibility' => 'all',
            'status' => 'published',
            'sku' => Str::random(8),
            'tax_status' => 'taxable',
            'tax_class' => '',
            'manage_stock' => true,
            'stock_quantity' => 99,
            'stock_status' => 'instock',
            'backorders' => 'no',
            'sold_individually' => false,
            'weight' => '10',
            'height' => '10',
            'width' => '10',
            'length' => '10',
            'shipping_class' => '',
            'reviews_allowed' => true,
        ];
    }
}
