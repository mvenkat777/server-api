<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('styles', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('code', 15);
            $table->string('name', 255);
            $table->string('line_id', 100);
            $table->string('tna_id', 100)->nullable();
            $table->string('techpack_id', 100)->nullable();
            $table->string('sample_submission_id', 100)->nullable();
            $table->string('order_id', 100)->nullable();
            $table->json('flat')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary('id');

            $table->foreign('line_id')->references('id')
                    ->on('lines')->onDelete('cascade');
            $table->foreign('tna_id')->references('id')
                    ->on('tna');
            $table->foreign('techpack_id')->references('id')
                    ->on('techpacks')->onDelete('cascade');
            $table->foreign('sample_submission_id')->references('id')
                    ->on('sample_submissions');
            $table->foreign('order_id')->references('id')
                    ->on('orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('styles');
    }
}
