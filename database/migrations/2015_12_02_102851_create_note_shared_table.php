<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteSharedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_shared', function (Blueprint $table) {
            $table->string('shared_by');
            $table->string('shared_to');
            $table->string('note_id');
            $table->timestamps();

            $table->foreign('note_id')
                    ->references('id')
                    ->on('notes')
                    ->onDelete('cascade');
            $table->foreign('shared_to')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->foreign('shared_by')
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
        Schema::drop('note_shared');
    }
}
