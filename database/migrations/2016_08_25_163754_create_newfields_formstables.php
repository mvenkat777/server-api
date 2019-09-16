<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewfieldsFormstables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_production_order', function (Blueprint $table) {
            $table->string('line_order_ref')->nullable();
            $table->string('currency')->nullable();
            $table->string('payment_method')->nullable();
        });

        Schema::table('form_purchase_order', function (Blueprint $table) {
            $table->string('currency')->nullable();
        });

        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->string('weight_of_shipment')->nullable();
            $table->string('no_of_carton')->nullable();
            \DB::statement('ALTER TABLE form_sales_order ADD COLUMN shipping_freight double precision;');
            \DB::statement('ALTER TABLE form_sales_order ADD COLUMN gross_invoice double precision;');
            \DB::statement('ALTER TABLE form_sales_order ADD COLUMN credit_fees double precision;');
            \DB::statement('ALTER TABLE form_sales_order ADD COLUMN deposit double precision;');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_production_order', function (Blueprint $table) {
            //
        });
    }
}
