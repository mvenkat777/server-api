<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('sample_container_id', 100);
            $table->string('title', 255);
            $table->string('type', 70);
            $table->string('author_id', 100);
            $table->json('image');
            $table->date('sent_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('vendor_id', 100)->nullable();
            $table->string('weight_or_quality', 20)->nullable();
            $table->string('fabric_or_content', 100)->nullable();
            $table->softDeletes();

            $table->primary(['id']);

            $table->foreign('sample_container_id')
                  ->references('id')
                  ->on('sample_containers')
                  ->onDelete('cascade');
            $table->foreign('author_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendors');

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
        Schema::drop('samples');
    }
}
