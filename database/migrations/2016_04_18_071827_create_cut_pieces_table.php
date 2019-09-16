<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCutPiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cut_pieces', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('techpack_id', 100);
            $table->string('name', 255);
            $table->json('image')->nullable();
            $table->string('amount', 255)->nullable();
            $table->string('fabric', 4)->nullable();
            $table->tinyInteger('non_flip')->nullable();
            $table->tinyInteger('x')->nullable();
            $table->tinyInteger('y')->nullable();
            $table->tinyInteger('xy')->nullable();

            $table->primary(['id']);
            $table->foreign('techpack_id')
                  ->references('id')
                  ->on('techpacks')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cut_pieces');
    }
}
