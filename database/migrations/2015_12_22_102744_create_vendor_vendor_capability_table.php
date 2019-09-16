<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorVendorCapabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_vendor_capability', function (Blueprint $table) {
            $table->string('vendor_id');
            $table->integer('vendor_capability_id')->unsigned();
            $table->boolean('inhouse')->default(false);
            $table->boolean('outsource')->default(false);
            $table->text('moq')->nullable();
            $table->string('capacity')->nullable();

            $table->foreign('vendor_capability_id')
                    ->references('id')
                    ->on('vendor_capability')
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
        Schema::drop('vendor_vendor_capability');
    }
}
