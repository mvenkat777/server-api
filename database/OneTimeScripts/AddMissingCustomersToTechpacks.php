<?php

use Illuminate\Database\Seeder;

class AddMissingCustomersToTechpacks extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $techpacks = \App\Techpack::select(['created_at'])->whereNull('customer_id')->where('visibility', true)->get();
        foreach ($techpacks as $techpack) {
            if (isset($techpack->meta->customer->name)) {
                $customerName = $techpack->meta->customer->name;
                $customer = \App\Customer::where('name', $customerName)->first();
                if (in_array($customerName, ['Saul Rajsky', 'Fred Mertz', 'DDE', 'App', 'KANDYMASK', 'Katie Fu', 'FCBD', 'Vishnu'])) {
                    continue;
                }
                if (!$customer && $customerName == 'MIA BELLA') {
                    $customer = \App\Customer::where('name', 'LIKE', $customerName . '%')->first();
                }

                if (!$customer && $customerName == 'ALOUETTE') {
                    $customer = \App\Customer::where('name', 'LIKE', ucfirst(strtolower($customerName)))->first();
                }
                if ($customer) {
                    $techpack->timestamps = false;
                    $meta = new \stdClass();
                    $meta = $techpack->meta;
                    $meta->customer->customerId = $customer->id;
                    $meta->customer->name = $customer->name;
                    $meta->customer->code = $customer->code;
                    $techpack->meta = $meta;
                    $techpack->customer_id = $customer->id;
                    $techpack->save();
                }
            };
        }
    }
}
