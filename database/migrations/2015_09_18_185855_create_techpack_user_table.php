<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechpackUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techpack_user', function(Blueprint $table)
        {
            $table->string('techpack_id',100);
            $table->foreign('techpack_id')->references('id')->on('techpacks')->onDelete('cascade');
            $table->string('user_id',100);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('permission', ['owner','can_read','can_edit'])->default('owner');
            $table->primary(['techpack_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('techpack_user');
    }

}
