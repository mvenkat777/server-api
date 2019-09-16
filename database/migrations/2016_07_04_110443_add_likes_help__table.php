<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLikesHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_like', function (Blueprint $table) {
            $table->string('user_id');
            $table->string('help_id');
            $table->boolean('is_like');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('help_id')->references('id')->on('help')->onDelete('cascade');
            $table->unique(['user_id', 'help_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('help_like');
    }
}
