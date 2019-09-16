<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormShippingNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_shipping_notice', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->text('bill_to_address')->nullable();
            $table->string('customer_po_number')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->text('ship_to_address')->nullable();
            $table->string('origin_country_goods')->nullable();
            $table->string('destination_country')->nullable();
            $table->string('shipment_mode')->nullable();
            $table->string('origin_country')->nullable();
            $table->timestamp('cancel_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->json('data');

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
        Schema::drop('form_shipping_notice');
    }
}
