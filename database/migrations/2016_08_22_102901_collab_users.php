<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CollabUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collab_users', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('collab_id');
            $table->string('user_id', 100);
            $table->text('invite_code')->nullable();
            $table->string('role', 20)->nullable();
            $table->boolean('is_active');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('collab_id')
                  ->references('id')
                  ->on('collabs')
                  ->onDelete('cascade');

            $table->foreign('user_id')
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
        Schema::drop('collab_users');
    }
}
