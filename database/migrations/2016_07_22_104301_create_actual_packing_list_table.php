<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualPackingListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_actual_packing_list', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->string('factory_name')->nullable();
            $table->text('factory_address')->nullable();
            $table->string('container_no')->nullable();
            $table->string('seal_no')->nullable();
            $table->timestamp('shipping_date')->nullable();
            $table->string('made_in')->nullable();
            $table->timestamp('packing_date')->nullable();
            $table->string('etd_vn')->nullable();
            $table->string('eta_sf')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('ship_to')->nullable();
            $table->string('attn')->nullable();
            $table->string('style')->nullable();
            $table->string('po')->nullable();
            $table->string('quantity')->nullable();
            $table->string('description')->nullable();
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
        Schema::drop('form_actual_packing_list');
    }
}
