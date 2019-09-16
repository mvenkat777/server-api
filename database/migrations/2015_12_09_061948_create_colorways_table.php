<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorwaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colorways', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('techpack_id', 100);
            $table->string('bom_line_item_id', 100);
            $table->json('colorway');
            $table->json('approval');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('techpack_id')
                  ->references('id')
                  ->on('techpacks')
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
        Schema::drop('colorways');
    }
}
