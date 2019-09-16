<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Customer;

class CustomersTableSeeder extends seeder
{
    public function run()
    {
    	$customer = factory(\App\Customer::class, 100)
    				->create()
    				->each(function($c){
    					$c->addresses()->save(factory(\App\Address::class)
    						->create(['label'=>'Registerd', 'is_primary'=>true]));
    					$c->addresses()->save(factory(\App\Address::class)
    						->create(['label'=>'Billing']));
    					$c->addresses()->save(factory(\App\Address::class)
    						->create(['label'=>'Shipping']));
    					$c->brands()->save(factory(\App\Brand::class)->make());
    					$cpartners = $c->partners()->save(factory(\App\CustomerPartner::class)
    						->create(['customer_id' => $c->id]));
    					$cpartners->address()->save(factory(\App\Address::class)
    						->create(['label'=>'partner']));
    					$cpartners->contact()->save(factory(\App\Contact::class)
    						->create(['label'=>'partner']));
    				});
    }
}