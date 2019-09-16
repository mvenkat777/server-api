<?php

use Illuminate\Database\Seeder;
use App\ProductList;

class ProductListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_lists')->delete();

       $products = [
            '01' => 'sports_bras', 
            '02' => 'camisoles', 
            '03' => 'shirts', 
            '04' => 'jumpsuits', 
            '05' => 'skirts', 
            '06' => 'long_sleeve_shirts', 
            '07' => 'shorts',
            '08' => 'underwear', 
            '09' => 'tanks', 
            '10' => 'sweaters', 
            '11' => 'outerwear', 
            '12' => 'short_sleeve_shirts', 
            '13' => 'dresses', 
            '14' => 'rompers',
            '15' => 'bodysuits',
            '16' => 'overalls', 
            '17' => 'coveralls', 
            '18' => 'sleepwear', 
            '19' => 'hats', 
            '20' => 'headbands', 
            '21' => 'scarves', 
            '22' => 'gloves', 
            '23' => 'socks',
            '24' => 'shoes', 
            '25' => 'belts', 
            '26' => 'blankets', 
            '27' => 'bags', 
            '28' => 'umbrellas', 
            '29' => 'capris', 
            '30' => 'leggings', 
            '31' => 'yoga_pants', 
            '32' => 'polo_shirts',
            '33' => 'sweatshirts', 
            '34' => 'gym_pants', 
            '35' => 'gym_shorts', 
            '36' => 'sweatpants', 
            '37' => 'shirt_neckbands', 
            '38' => 'tank_binding',
            '39' => 'camisole_straps', 
            '40' => 'sweatshirts_neckbands', 
            '41' => 'cuffs', 
            '42' => 'gym_whorts_waistbands', 
            '43' => 'sweatpants_cuffs',
            '44' => 'dresses_skirts', 
            '45' => 'dress_shirts', 
            '46' => 'pants', 
            '47' => 'swimwear', 
            '48' => 'one-pieces', 
            '49' => 'top', 
            '50' => 'bottom', 
            '51' => 'accessories',
            '52' => 't-shirts', 
            '53' => 'others'
    ]; 
        
        foreach ($products as $code => $product) {
            $newProduct = new ProductList();
            $newProduct->code = $code;
            $newProduct->product = $product;
            $newProduct->save();
        }
    }
}
