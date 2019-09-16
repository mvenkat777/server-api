<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleSubmissionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_submission_categories', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('sample_submission_id', 100);
            $table->string('name', 70);
            $table->text('content');
            $table->timestamps();

            $table->foreign('sample_submission_id')
                  ->references('id')
                  ->on('sample_submissions')
                  ->onDelete('cascade');

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sample_submission_categories');
    }
}
