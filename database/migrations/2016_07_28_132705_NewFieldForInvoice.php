<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFieldForInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_order_shipment_reconciliation', function (Blueprint $table) {
            $table->json('data')->nullable();

            });
        Schema::table('form_commercial_invoice', function (Blueprint $table) {
            $table->string('in_words')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_order_shipment_reconciliation', function (Blueprint $table) {
            $table->dropColumn('data');

             });
        Schema::table('form_commercial_invoice', function (Blueprint $table) {
            $table->dropColumn('in_words');

             });

    }
}
