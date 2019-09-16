<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_purchase_order', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->string('se_issuing_office')->nullable();
            $table->string('vendor')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('ship_to')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('inco_term')->nullable();
            $table->string('payment_terms')->nullable();
            $table->timestamp('factory_ship_date')->nullable();
            $table->timestamp('factory_cancel_date')->nullable();
            $table->string('additional_details_as_needed')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('authorized_by')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();



            $table->foreign('form_user_id')->references('id')->on('form_user')->onDelete('cascade');
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
