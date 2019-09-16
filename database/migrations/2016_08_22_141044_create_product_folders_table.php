<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_folders', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('board_id');
            $table->string('name', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('archived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_folders');
    }
}
