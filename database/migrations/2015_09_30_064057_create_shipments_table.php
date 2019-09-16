<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->string('id');
            $table->string('shipment_type');
            $table->timestamp('shipped_date');
            $table->string('shipped_from');
            $table->string('shipped_destination');
            $table->text('item_details');
            $table->string('tracking_id');
            $table->string('tracking_provider')->nullable();
            $table->enum('tracking_status',['shipped','delivered'])->default('shipped');
            $table->string('user_id');
            $table->string('techpack_id')->nullable();
            $table->string('product_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipments');
    }
}
