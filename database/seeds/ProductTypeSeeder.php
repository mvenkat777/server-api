<?php

use App\ProductCategory;
use App\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('product_types')->delete();

        $productTypes = [
            '01' => 'top',
            '02' => 'bottom',
            '03' => 'one_piece',
            '04' => 'scarf',
            '05' => 'hat',
            '06' => 'bag',
            '99' => 'others'
        ];

        foreach ($productTypes as $code => $productType) {
            $newProduct = new ProductType();
            $newProduct->code = $code;
            $newProduct->product_type = $productType;
            $newProduct->save();
        }
    }
}
