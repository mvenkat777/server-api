<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorVendorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_vendor_types', function (Blueprint $table) {
            $table->string('vendor_id');
            $table->integer('vendor_type_id')->unsigned();

            $table->foreign('vendor_type_id')
                    ->references('id')
                    ->on('vendor_types')
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
        Schema::drop('vendor_vendor_types');
    }
}
