<?php

use Illuminate\Database\Seeder;

class RemoveCustomerPrefix extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $customers = \App\Customer::get();
        foreach ($customers as $customer) {
            $code = preg_replace("/(^CU)|(^C-)/", "", $customer->code);
            \DB::beginTransaction();
            \App\Customer::find($customer->id)->update(['code' => $code]);
            \DB::commit();
        }
    }
}