<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPartnerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_partner_address', function (Blueprint $table) {
            $table->string('customer_partner_id');
            $table->integer('address_id')->unsigned();

            $table->foreign('address_id')
                    ->references('id')
                    ->on('address')
                    ->onDelete('cascade');

             $table->foreign('customer_partner_id')
                    ->references('id')
                    ->on('customer_partners')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_partner_address');
    }
}
