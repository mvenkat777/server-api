<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Techpack;

class TechpackTableSeeder extends seeder
{
    public function run()
    {
        $faker = Faker::create();

        $categoryList = ['girls', 'boys', 'babies', 'men'];

        $productList = [
            'sports_bras', 'camisoles', 'shirts', 'jumpsuits', 'skirts', 'long_sleeve_shirts', 'shorts',
            'underwear', 'tanks', 'sweaters', 'outerwear', 'short_sleeve_shirts', 'dresses', 'rompers',
            'bodysuits','overalls', 'coveralls', 'sleepwear', 'hats', 'headbands', 'scarves', 'gloves', 'socks',
            'shoes', 'belts', 'blankets', 'bags', 'umbrellas', 'capris', 'leggings', 'yoga_pants', 'polo_shirts',
            'sweatshirts', 'gym_pants', 'gym_shorts', 'sweatpants', 'shirt_neckbands', 'tank_binding',
            'camisole_straps', 'sweatshirts_neckbands', 'cuffs', 'gym_whorts_waistbands', 'sweatpants_cuffs',
            'dresses_skirts', 'dress_shirts', 'pants', 'swimwear', 'one-pieces', 'top', 'bottom', 'accessories',
            'na', 't-shirts'
        ];

        $productTypeList = [
            'SLEEVELESS', 'SHORT SLEEVE', 'LONG SLEEVE', 'JACKET', 'PANTY', 'SHORT', 'PANT', 'SKIRT', 'SKORT',
            'ROMPER', 'JUMPSUIT', 'OVERALLS', 'HAT', 'GLOVES', 'BELT', 'SCARF', 'SOCKS', 'SHOES', 'BAG', 'ONESIES',
            'COVERALLS', 'BIB', 'OTHERS'
        ];

        $sizeTypeList = [
            'alpha', 'numeric', 'alphanumeric'
        ];

        $seasonsList = [
            'Fall', 'Winter', 'Summer'
        ];

        $stagesList = ['costing', 'proto', 'pp_sample', 'production'];

        for ($i = 0; $i < 100; $i++) {
            $name = 'techpack'.rand(0, 500);
            $styleCode = $faker->uuid;
            $category = $categoryList[array_rand($categoryList)];
            $product = $productList[array_rand($productList)];
            $productType = $productTypeList[array_rand($productTypeList)];
            $sizeType = $sizeTypeList[array_rand($sizeTypeList)];
            $season = $seasonsList[array_rand($seasonsList)] . ' ' . $faker->numberBetween(2015, 2017);
            $stage = $stagesList[array_rand($stagesList)];
            $collection = $faker->text;


            $meta = [
                'name' => $name,
                'styleCode' => $styleCode,
                'category' => $category,
                'product' => $product,
                'productType' => $productType,
                'collection' => $collection,
                'sizeType' => $sizeType,
                'season' => $season,
                'stage' => $stage,
                'revision' => 0,
                'state' => '',
                'isPublished' => 0,
                'isBuilderTechpack' => 0,
                'visibility' => true,
                'image' => '',
                'tags' => ['techpack'],
            ];

            $techpack = factory(App\Techpack::class)
                        ->create(
                            [
                                'category' => $category,
                                'product' => $product,
                                'product_type' => $productType,
                                'size_type' => $sizeType,
                                'stage' => $stage,
                                'style_code' => $styleCode,
                                'name' => $name,
                                'category' => $category,
                                'collection' => $collection,
                                'season' => $season,
                                'stage' => $stage,
                                'meta' => $meta,
                            ]
                        );
        }

    }
}
