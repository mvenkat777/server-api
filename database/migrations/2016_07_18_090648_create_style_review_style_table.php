<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleReviewStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_review_style', function (Blueprint $table) {
            $table->string('style_id');
            $table->integer('style_review_id');
            $table->json('owner')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->json('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->json('unapproved_by')->nullable();
            $table->timestamp('unapproved_at')->nullable();

            $table->foreign('style_id')
                  ->references('id')
                  ->on('styles')
                  ->onDelete('cascade');

            $table->foreign('style_review_id')
                  ->references('id')
                  ->on('style_review')
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
        Schema::drop('style_review_style');
    }
}
