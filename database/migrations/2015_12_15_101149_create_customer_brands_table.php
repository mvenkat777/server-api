<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('customer_brands')) {
            \DB::statement("DELETE from migrations where migration = '2015_12_15_101149_create_vendor_customer_brands_table'");
            return;
        }
        Schema::create('customer_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id');
            $table->string('brand');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_brands');
    }
}
