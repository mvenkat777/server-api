<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collabs', function (Blueprint $table) {
            $table->uuid('id', 100);
            $table->string('customer_id', 100);
            $table->string('name', 255);
            $table->string('url', 35)->unique();
            $table->json('logo');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
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
        Schema::drop('collabs');
    }
}
