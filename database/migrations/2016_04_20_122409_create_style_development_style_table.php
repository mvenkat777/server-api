<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleDevelopmentStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_development_style', function (Blueprint $table) {
            $table->string('style_id');
            $table->integer('style_development_id');
            $table->json('owner');
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->json('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->json('unapproved_by')->nullable();
            $table->timestamp('unapproved_at')->nullable();

            $table->foreign('style_id')
                  ->references('id')
                  ->on('styles')
                  ->onDelete('cascade');

            $table->foreign('style_development_id')
                  ->references('id')
                  ->on('style_developments')
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
        Schema::drop('style_development_style');
    }
}
