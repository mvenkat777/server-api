<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleSubmissionAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_submission_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sample_submission_id', 100);
            $table->string('sample_submission_categories_id', 100);
            $table->json('file');
            $table->json('uploaded_by');
            $table->timestamps();

            $table->foreign('sample_submission_id')
                  ->references('id')
                  ->on('sample_submissions')
                  ->onDelete('cascade');

            $table->foreign('sample_submission_categories_id')
                  ->references('id')
                  ->on('sample_submission_categories')
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
        Schema::drop('sample_submission_attachments');
    }
}
