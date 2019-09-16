<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormShippingCustomerOutbondsNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_customer_outbound_notification', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->string('vendor')->nullable();
            $table->string('customer')->nullable();
            $table->string('customer_po')->nullable();
            $table->string('se_po')->nullable();
            $table->string('total_shipped')->nullable();
            $table->timestamp('ex_factory_date')->nullable();
            $table->timestamp('eta_customer')->nullable();
            $table->timestamp('archived_at')->nullable();
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
        Schema::drop('form_customer_outbound_notif');
    }
}
