<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id');
            $table->string('customer_id');
            $table->string('value')->nullable();
            $table->string('quantity')->nullable();
            $table->string('size')->nullable();
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            
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
        Schema::drop('orders');
    }
}
