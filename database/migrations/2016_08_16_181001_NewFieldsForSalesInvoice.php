<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFieldsForSalesInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->dropColumn('customer');
            $table->dropColumn('customer_id');
        });

        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->json('customer')->nullable();
            $table->json('customer_id')->nullable();
            $table->json('vendor_id')->nullable();
        });

        Schema::table('form_purchase_order', function (Blueprint $table) {
            $table->dropColumn('se_issuing_office');
            $table->dropColumn('vendor');
            $table->dropColumn('customer_id');
        });

        Schema::table('form_purchase_order', function (Blueprint $table) {
            $table->json('vendor')->nullable();
            $table->json('customer_id')->nullable();
            $table->json('se_issuing_office')->nullable();
            $table->json('total_quantity')->nullable();
            $table->json('total')->nullable();
        });
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
