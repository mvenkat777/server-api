<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorVendorServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_vendor_service', function (Blueprint $table) {
            $table->string('vendor_id');
            $table->integer('vendor_service_id')->unsigned();

            $table->foreign('vendor_service_id')
                    ->references('id')
                    ->on('vendor_service')
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
        Schema::drop('vendor_vendor_service');
    }
}
