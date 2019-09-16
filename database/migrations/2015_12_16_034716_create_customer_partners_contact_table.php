<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPartnersContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_partners_contact', function (Blueprint $table) {
            $table->integer('contact_id')->unsigned();
            $table->string('customer_partner_id');

            $table->foreign('customer_partner_id')
                    ->references('id')
                    ->on('customer_partners')
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
        Schema::drop('customer_partners_contact');
    }
}
