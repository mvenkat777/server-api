<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFolderPickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_folder_pick', function (Blueprint $table) {
            $table->uuid('product_folder_id');
            $table->uuid('pick_id');

            $table->foreign('product_folder_id')
                  ->references('id')
                  ->on('product_folders')
                  ->onDelete('cascade');

            $table->foreign('pick_id')
                  ->references('id')
                  ->on('picks')
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
        Schema::drop('product_folder_pick');
    }
}
