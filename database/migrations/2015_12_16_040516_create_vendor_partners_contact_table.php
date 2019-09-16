<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPartnersContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_partners_contact', function (Blueprint $table) {
            $table->integer('contact_id')->unsigned();
            $table->string('vendor_partner_id');

            $table->foreign('vendor_partner_id')
                    ->references('id')
                    ->on('vendor_partners')
                    ->onDelete('cascade');

             $table->foreign('contact_id')
                    ->references('id')
                    ->on('contacts')
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
        Schema::drop('vendor_partners_contact');
    }
}
