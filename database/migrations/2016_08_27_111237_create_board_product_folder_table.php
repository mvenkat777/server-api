<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardProductFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_product_folder', function (Blueprint $table) {
            $table->uuid('board_id');
            $table->uuid('product_folder_id');

            $table->foreign('board_id')
                  ->references('id')
                  ->on('boards')
                  ->onDelete('cascade');

            $table->foreign('product_folder_id')
                  ->references('id')
                  ->on('product_folders')
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
        Schema::drop('board_product_folder');
    }
}
