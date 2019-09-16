<?php

use App\ProductCategory;
use App\ProductList;
use App\ProductType;
use Illuminate\Database\Seeder;

class ProductCategoriesAndListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->delete();

        $categories = [
            '01'=> 'men',
            '02'=> 'women',
            '03'=> 'boys',
            '04'=> 'girls',
            '05'=> 'juniors',
            '06'=> 'plus_women',
            '07'=> 'big/tall_men',
            '08'=> 'babies',
            '09'=> 'toddler',
            '10'=> 'infant',
            '11'=> 'kids',
            '12'=> 'unisex',
            '13'=> 'accessories',
            '99'=> 'others',
        ];

        foreach ($categories as $code => $category) {
            $newCategory = new ProductCategory();
            $newCategory->code = $code;
            $newCategory->category = $category;
            $newCategory->save();
        }

        DB::table('product_types')->delete();

        $productTypes = [
            '01' => 'top',
            '02' => 'bottom',
            '03' => 'one_piece',
            '04' => 'scarf',
            '05' => 'hat',
            '06' => 'bag',
            '07' => 'blanket',
            '99' => 'others'
        ];

        foreach ($productTypes as $code => $productType) {
            $newProduct = new ProductType();
            $newProduct->code = $code;
            $newProduct->product_type = $productType;
            $newProduct->save();
        }

        DB::table('product_lists')->delete();

       $products = [
            '001' => 'tank',
            '002' => 'sleeveless',
            '003' => 'top_short_sleeve',
            '004' => 'top_long_sleeve',
            '005' => 'shorts',
            '006' => 'pants',
            '007' => 'dress',
            '008' => 'skirt',
            '009' => 'coveralls',
            '010' => 'one_piece',
            '011' => 'scarf',
            '012' => 'romper',
            '013' => 'onesize',
            '014' => 'jumpsuit',
            '015' => 'one_piece_swim',
            '016' => 'bikini_top/Bra',
            '017' => 'swim/intimate_bottom',
            '018' => 'sport_bra',
            '019' => 'hat',
            '020' => 'headbands',
            '021' => 'outerwear_jacket',
            '022' => 'outerwear_pant',
            '023' => 'sock',
            '024' => 'glove',
            '025' => 'belt',
            '026' => 'tote_bag',
            '027' => 'purse',
            '028' => 'pouch',
            '029' => 'blazer',
            '999' => 'others'
        ];

        $productTypeCode = [
            '001' => '01',
            '002' => '01',
            '003' => '01',
            '004' => '01',
            '005' => '02',
            '006' => '02',
            '007' => '03',
            '008' => '02',
            '009' => '03',
            '010' => '03',
            '011' => '04',
            '012' => '03',
            '013' => '03',
            '014' => '03',
            '015' => '03',
            '016' => '01',
            '017' => '02',
            '018' => '01',
            '019' => '05',
            '020' => '99',
            '021' => '01',
            '022' => '02',
            '023' => '99',
            '024' => '99',
            '025' => '99',
            '026' => '06',
            '027' => '06',
            '028' => '06',
            '029' => '01',
            '999' => '99'
        ];

        foreach ($products as $code => $product) {
            $newProduct = new ProductList();
            $newProduct->code = $code;
            $newProduct->product = $product;
            $newProduct->product_type_code = $productTypeCode[$code];
            $newProduct->save();
        }

    }
}
