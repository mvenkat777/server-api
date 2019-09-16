<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTNATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna', function (Blueprint $table) {
            $table->string('id');
            $table->string('title');
            $table->string('creator_id');
            $table->string('order_id');
            $table->string('order_quantity');
            $table->string('techpack_id');
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('customer_code');
            $table->string('vendor_id');
            $table->string('representor_id');
            $table->string('style_id')->nullable();
            $table->string('style_range')->nullable();
            $table->string('style_description')->nullable();
            $table->integer('tna_type_id')->unsigned()->default(1);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('target_date');
            $table->boolean('is_published')->default(false);
            $table->integer('tna_state_id')->unsigned()->default(1);
            $table->json('items_order');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('techpack_id')->references('id')->on('techpacks')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('representor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tna_type_id')->references('id')->on('tna_type')->onDelete('cascade');
            $table->foreign('tna_state_id')->references('id')->on('tna_state')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna');
    }
}
