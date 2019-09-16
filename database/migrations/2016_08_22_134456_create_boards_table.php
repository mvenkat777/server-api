<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pick_comments');
        Schema::dropIfExists('quote_requests');
        Schema::dropIfExists('sample_requests');
        Schema::dropIfExists('picks');
        Schema::dropIfExists('boards');
        Schema::create('boards', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('archived_at')->nullable();

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
        Schema::drop('boards');
    }
}
