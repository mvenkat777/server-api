<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Vendor;

class VendorsTableSeeder extends seeder
{
    public function run()
    {
    	$vendor = factory(\App\Vendor::class, 100)
    				->create()
    				->each(function($v){
    					$v->addresses()->save(factory(\App\Address::class)
    						->create(['label'=>'Registerd', 'is_primary'=>true]));
    					$v->addresses()->save(factory(\App\Address::class)
    						->create(['label'=>'Mailing']));
    					$v->addresses()->save(factory(\App\Address::class)
    						->create(['label'=>'Factory']));
    					$v->banks()->save(factory(\App\BankDetail::class)->make());
    					$vpartners = $v->partners()->save(factory(\App\VendorPartner::class)
    						->create(['vendor_id' => $v->id]));
    					$vpartners->address()->save(factory(\App\Address::class)
    						->create(['label'=>'partner']));
    					$vpartners->contact()->save(factory(\App\Contact::class)
    						->create(['label'=>'partner']));
    						
    				});
    }
}