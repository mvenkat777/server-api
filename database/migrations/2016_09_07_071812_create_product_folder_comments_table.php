<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFolderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_folder_comments', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('product_folder_id');
            $table->text('comment');
            $table->string('commentator_id', 100);
            $table->timestamps();

            $table->foreign('product_folder_id')
                  ->references('id')
                  ->on('product_folders')
                  ->onDelete('cascade');

            $table->foreign('commentator_id')
                  ->references('id')
                  ->on('users')
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
        Schema::drop('product_folder_comments');
    }
}
