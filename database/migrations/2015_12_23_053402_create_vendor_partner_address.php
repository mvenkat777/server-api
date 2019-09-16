<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPartnerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_partner_address', function (Blueprint $table) {
             $table->string('vendor_partner_id');
            $table->integer('address_id')->unsigned();

            $table->foreign('address_id')
                    ->references('id')
                    ->on('address')
                    ->onDelete('cascade');

             $table->foreign('vendor_partner_id')
                    ->references('id')
                    ->on('vendor_partners')
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
        Schema::drop('vendor_partner_address');
    }
}
