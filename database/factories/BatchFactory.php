<?php

namespace Database\Factories;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

use DB;

class BatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Batch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products = DB::table('products')->pluck('id')->toArray();
        $productId = $products[array_rand($products, 1)];

        $images = DB::table('images')->pluck('id')->toArray();
        $imageId = $images[array_rand($images, 1)];

        return [
            'product_id' => $productId,
            'regular_price' => rand(10, 8000),
            'sale_price' => rand(0, 1000),
            'image_id' => $imageId,
            'language' => 'nl'
        ];
    }
}
