<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaItemVisibilityTnaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_item_visibility_tna_item', function (Blueprint $table) {
            $table->string('tna_item_id');
            $table->integer('tna_item_visibility_id');
            $table->timestamps();

            $table->foreign('tna_item_id')->references('id')->on('tna_items')->onDelete('cascade');
            $table->foreign('tna_item_visibility_id')->references('id')->on('tna_item_visibility')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna_item_visibility_tna_item');
    }
}
