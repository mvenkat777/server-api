<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorVendorPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_vendor_payment_terms', function (Blueprint $table) {
            $table->string('vendor_id');
            $table->integer('vendor_payment_terms_id')->unsigned();

            $table->foreign('vendor_payment_terms_id')
                    ->references('id')
                    ->on('vendor_payment_terms')
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
        Schema::drop('vendor_vendor_payment_terms');
    }
}
