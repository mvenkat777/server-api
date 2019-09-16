<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleCriteriaAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_criteria_attachments', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('sample_criteria_id', 100);
            $table->json('file');
            $table->string('uploader_id', 100);

            $table->primary(['id']);
            $table->foreign('sample_criteria_id')
                  ->references('id')
                  ->on('sample_criterias')
                  ->onDelete('cascade');
            $table->foreign('uploader_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

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
        Schema::drop('sample_criteria_attachments');
    }
}
