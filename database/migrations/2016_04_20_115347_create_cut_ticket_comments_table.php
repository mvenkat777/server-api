<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCutTicketCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cut_ticket_comments', function (Blueprint $table) {
            $table->string('id', 100);
            $table->text('comment');
            $table->string('commented_by', 100);
            $table->string('techpack_id', 100);
            $table->json('file')->nullable();
            
            $table->primary(['id']);
            $table->foreign('commented_by')
                  ->references('id')
                  ->on('users');

            $table->foreign('techpack_id')
                  ->references('id')
                  ->on('techpacks')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cut_ticket_comments');
    }
}
