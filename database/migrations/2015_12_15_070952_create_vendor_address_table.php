<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_address', function (Blueprint $table) {
            $table->string('vendor_id');
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')
                    ->references('id')
                    ->on('address')
                    ->onDelete('cascade');
            $table->foreign('vendor_id')
                    ->references('id')
                    ->on('vendors')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendor_address');
    }
}
