<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('note_id');
            $table->string('comment');
            $table->string('commented_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('note_id')
                    ->references('id')
                    ->on('notes')
                    ->onDelete('cascade');
            $table->foreign('commented_by')
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
        Schema::drop('note_comments');
    }
}
