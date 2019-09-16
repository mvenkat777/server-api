<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductionOrderField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('form_production_order', function (Blueprint $table) {
            $table->string('po')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_terms')->nullable();
            $table->string('inco_term')->nullable();
            $table->json('sizes')->nullable();
            $table->string('payment_terms')->nullable();
            $table->timestamp('factory_ship_date')->nullable();
            $table->timestamp('factory_cancel_date')->nullable();
            $table->string('additional_details_as_needed')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('authorized_by')->nullable();
            $table->json('data')->nullable();

        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('form_production_order');
    }
}
