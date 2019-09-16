<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerCustomerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_customer_types', function (Blueprint $table) {
            $table->string('customer_id');
            $table->integer('customer_type_id')->unsigned();

            $table->foreign('customer_type_id')
                    ->references('id')
                    ->on('customer_types')
                    ->onDelete('cascade');

             $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
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
        Schema::drop('customer_customer_types');
    }
}
