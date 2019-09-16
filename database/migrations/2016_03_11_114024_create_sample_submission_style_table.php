<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleSubmissionStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_submission_style', function (Blueprint $table) {
            $table->string('style_id', 100);
            $table->string('sample_submission_id', 100);

            $table->foreign('style_id')->references('id')
                ->on('styles')
                ->onDelete('cascade');

            $table->foreign('sample_submission_id')->references('id')
                ->on('sample_submissions')
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
        Schema::drop('sample_submission_style');
    }
}
