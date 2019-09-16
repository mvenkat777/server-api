<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechpackCutTicketNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techpack_cut_ticket_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('techpack_id');
            $table->text('note')->nullable();
            $table->json('image')->nullable();
            $table->timestamps();

            $table->foreign('techpack_id')
                    ->references('id')
                    ->on('techpacks')
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
        Schema::drop('techpack_cut_ticket_notes');
    }
}
