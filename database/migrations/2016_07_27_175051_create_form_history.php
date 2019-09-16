<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_history', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->json('his_form_user_olddata')->nullable();
            $table->json('his_form_user_newdata')->nullable();
            $table->json('his_form_olddata')->nullable();
            $table->json('his_form_newdata')->nullable();
            $table->text('trigger_table')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_history', function (Blueprint $table) {
            //
        });
    }
}
