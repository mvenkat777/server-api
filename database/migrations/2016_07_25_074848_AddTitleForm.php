<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->string('title')->nullable();
        }); 
        Schema::table('form_shipping_notice', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
        Schema::table('form_order_shipment_reconciliation', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
        Schema::table('form_customer_outbound_notification', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
        Schema::table('form_production_order', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
        Schema::table('form_commercial_invoice', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
        Schema::table('form_actual_packing_list', function (Blueprint $table) {
            $table->string('title')->nullable();
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
             $table->dropColumn('title');
        }); 
        Schema::table('form_shipping_notice', function (Blueprint $table) {
           $table->dropColumn('title');
        });
        Schema::table('form_order_shipment_reconciliation', function (Blueprint $table) {
            $table->dropColumn('title');
        });
        Schema::table('form_customer_outbound_notification', function (Blueprint $table) {
            $table->dropColumn('title');
        });
        Schema::table('form_production_order', function (Blueprint $table) {
            $table->dropColumn('title');
        });
        Schema::table('form_commercial_invoice', function (Blueprint $table) {
            $table->dropColumn('title');
        });
        Schema::table('form_actual_packing_list', function (Blueprint $table) {
            $table->dropColumn('title');
        });

    }
}
