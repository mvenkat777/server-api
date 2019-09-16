<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_sales_order', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->text('bill_to_address')->nullable();
            $table->string('customer_po_number')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('country_of_origin_of_goods')->nullable();
            $table->string('final_destination_of_goods')->nullable();
            $table->string('mode_of_shipment')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->timestamp('cancel_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('archived_at')->nullable();
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
        Schema::drop('sales_order');
    }
}
