<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDetailsAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_address', function (Blueprint $table) {
            $table->integer('bank_id');
            $table->integer('address_id');

            $table->foreign('bank_id')
                    ->references('id')
                    ->on('bank_details')
                    ->onDelete('cascade');

            $table->foreign('address_id')
                    ->references('id')
                    ->on('address')
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
        Schema::drop('bank_address');
    }
}
