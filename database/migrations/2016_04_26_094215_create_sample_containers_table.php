<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_containers', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('techpack_id', 100);
            $table->string('customer_id', 100);
            $table->string('style_code', 100);
            $table->json('flat_image', 100);

            $table->primary(['id']);

            $table->foreign('techpack_id')
                  ->references('id')
                  ->on('techpacks')
                  ->onDelete('cascade');
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers');

            $table->softDeletes();
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
        Schema::drop('sample_containers');
    }
}
