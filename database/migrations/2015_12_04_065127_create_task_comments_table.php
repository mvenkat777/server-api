<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_comments', function (Blueprint $table) {
            $table->string('id');
            $table->string('task_id');
            $table->string('creator_id');
            $table->enum('type', ['text', 'file'])->default('text');
            $table->text('data');

            $table->softDeletes();
            $table->timestamps();
            $table->primary( 'id' );

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task_comments');
    }
}
