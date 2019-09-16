<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechpackCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techpack_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->string('techpack_id', 100);
            $table->string('user_id', 100);
            $table->longText('comment');
            $table->longText('file')->nullable();
            $table->timestamps();

            $table->foreign('techpack_id')->references('id')
                                         ->on('techpacks')
                                         ->onDelete('cascade');

            $table->foreign('user_id')->references('id')
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
        Schema::drop('techpack_comments');
    }
}
