<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollabBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collab_board', function (Blueprint $table) {
            $table->uuid('collab_id');
            $table->uuid('board_id');

            $table->foreign('collab_id')
                  ->references('id')
                  ->on('collabs')
                  ->onDelete('cascade');

            $table->foreign('board_id')
                  ->references('id')
                  ->on('boards')
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
        Schema::drop('collab_board');
    }
}
