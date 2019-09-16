<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateEntriesForCustomerAlouette extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customer = \App\Customer::where('id', '77054de3-5541-4306-9b70-c3b973d2aa80')
                              ->first();
        if ($customer) {
            $customer->delete();
        }

         
        $techpacks = \App\Techpack::all();

        foreach ($techpacks as $techpack) {
            if (isset($techpack->meta->customer) && $techpack->meta->customer->name == 'ALOUETTE') {
                $techpack->customer_id = "3d5d94cc-9c3c-4ce9-89da-19168c1f1131";
                $techpack->timestamps = false;
                $techpack->update();
            }
        }
        \App\Line::where('customer_id', '77054de3-5541-4306-9b70-c3b973d2aa80')->update(['customer_id' => '3d5d94cc-9c3c-4ce9-89da-19168c1f1131']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
