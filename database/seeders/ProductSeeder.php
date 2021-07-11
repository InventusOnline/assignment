<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Image;

use DB;

class ProductSeeder extends Seeder
{
    protected $amount = 2000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        Product::factory()
            ->count($this->amount)
            ->create();

        Image::factory()
            ->count($this->amount)
            ->create();

        Batch::factory()
            ->count($this->amount)
            ->create();

        $batches = Batch::all();
        $images = DB::table('images')->pluck('id')->toArray();

        //Create attributes & terms
        $this->buildAttributes();
        $attributes = DB::table('attributes')->pluck('id')->toArray();

        //Add data to batches
        foreach($batches as $batch) {
            //Connect images
            for($i = 0; $i < rand(1,8); $i++) {
                $randomImage = $images[array_rand($images, 1)];

                DB::table('batch_images')->insert([
                    'batch_id' => $batch->id,
                    'image_id' => $randomImage
                ]);
            }
            
            //Connect texts
            $name = $faker->name();
            DB::table('batch_texts')->insert([
                'batch_id' => $batch->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->realText($faker->numberBetween(100,300)),
                'short_description' => $faker->realText($faker->numberBetween(10,100)),
                'purchase_note' => $faker->realText($faker->numberBetween(10,50))
            ]);

            //Connect attributes
            for($i = 0; $i < rand(2,6); $i++) {
                $randomAttribute = $attributes[array_rand($attributes, 1)];
                
                $check = DB::table('batch_attributes')->where('batch_id', $batch->id)
                ->where('attribute_id', $randomAttribute)->first();

                if($check) continue;

                DB::table('batch_attributes')->insert([
                    'batch_id' => $batch->id,
                    'attribute_id' => $randomAttribute
                ]);

                //Get terms
                $terms = DB::table('attribute_terms')->where('attribute_id', $randomAttribute)->pluck('id')->toArray();
                
                for($i = 0; $i < rand(1,9); $i++) {
                    $randomTerm = $terms[array_rand($terms, 1)];

                    $check = DB::table('batch_attribute_terms')->where('batch_id', $batch->id)
                    ->where('attribute_id', $randomAttribute)
                    ->where('term_id', $randomTerm)->first();
                    if($check) continue;

                    DB::table('batch_attribute_terms')->insert([
                        'batch_id' => $batch->id,
                        'attribute_id' => $randomAttribute,
                        'term_id' => $randomTerm
                    ]);
                }
            }
        }
        
    }

    private function buildAttributes() {
        $faker = \Faker\Factory::create();

        $attributes = [
            [
                'name' => 'Color',
                'terms' => ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Black', 'White', 'Orange']
            ],
            [
                'name' => 'Manufacturing Year',
                'terms' => ['1900', '1920', '1940', '1950', '1970', '2000', '2002', '2005', '2007', '2011', '2013', '2015', '2017', '2018', '2020', '2021']
            ],
            [
                'name' => 'Aansluiting',
                'terms' => ['3-Fase', 'Batterij', 'Euro stekker (inclusief)', 'Exclusief stekker', 'Laag voltage', 'Netstroom', 'Solar']
            ],
            [
                'name' => 'Aantal in doos',
                'terms' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']
            ],
            [
                'name' => 'Volume',
                'terms' => ['8 Liter', '12 Liter', '18 Liter', '24 Liter', '26 Liter', '28 Liter', '32 Liter', '38 Liter', '80 Liter', '120 Liter', '128 Liter']
            ],
            [
                'name' => 'Vorm',
                'terms' => ['Square', 'Round', 'Oval', 'Pentagon', 'Hexagon', 'Octagon']
            ]
        ];

        foreach($attributes as $attribute) {
            $db_attribute = DB::table('attributes')->insert([
                'name' => $attribute['name'],
                'slug' => Str::slug($attribute['name'])
            ]);
            $db_attribute = DB::table('attributes')->where('name', $attribute['name'])->first();

            foreach($attribute['terms'] as $term) {
                DB::table('attribute_terms')->insert([
                    'name' => $term,
                    'slug' => Str::slug($term),
                    'description' => $faker->text(),
                    'attribute_id' => $db_attribute->id
                ]);
            }
        }
    }
}
