<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picks', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('product_folder_id');
            $table->string('name', 255);
            $table->json('pick');
            $table->string('uploader_id', 100);
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('archived_at')->nullable();

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
        Schema::drop('picks');
    }
}
