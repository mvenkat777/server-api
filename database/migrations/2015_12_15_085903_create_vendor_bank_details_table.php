<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_bank_details', function (Blueprint $table) {
            $table->integer('bank_id')->unsigned();
            $table->string('vendor_id');
            $table->foreign('bank_id')
                    ->references('id')
                    ->on('bank_details')
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
        Schema::drop('vendor_bank_details');
    }
}
