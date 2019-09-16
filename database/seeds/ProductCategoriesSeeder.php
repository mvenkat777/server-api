<?php

use Illuminate\Database\Seeder;
use App\ProductCategory;

class ProductCategoriesSeeder extends Seeder
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
            '05'=> 'babies',
            '06'=> 'accessories',
            '99'=> 'others',
        ];
        
        foreach ($categories as $code => $category) {
            $newCategory = new ProductCategory();
            $newCategory->code = $code;
            $newCategory->category = $category;
            $newCategory->save();
        }
    }
}
