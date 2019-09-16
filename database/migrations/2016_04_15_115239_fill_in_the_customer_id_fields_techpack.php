<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Techpack;
use App\Customer;

class FillInTheCustomerIdFieldsTechpack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
            $techpacks = Techpack::all();

            foreach ($techpacks as $techpack) {
                if (isset($techpack->meta->customer)) {
                    $techpack->timestamps = false;
                    $customer = Customer::where('name', $techpack->meta->customer->name)->first();
                    if ($customer) {
                        $techpack->customer_id = $customer->id;
                    }
                    $techpack->update();
                }
            }
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
