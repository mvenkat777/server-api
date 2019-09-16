<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSalesInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->string('customer')->nullable();
            $table->string('customer_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->dropColumn('customer');
            $table->dropColumn('customer_id');

             });
    }
}
