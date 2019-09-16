<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollabInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collab_invites', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('collab_id');
            $table->string('user_id', 100);
            $table->string('invite_code', 255)->nullable();
            $table->boolean('is_active');
            $table->string('permission', 30);
            $table->timestamps();

            $table->foreign('collab_id')
                  ->references('id')
                  ->on('collabs')
                  ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

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
        Schema::drop('collab_invites');
    }
}
