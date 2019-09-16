<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleShippedStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_shipped_style', function (Blueprint $table) {
            $table->string('style_id');
            $table->integer('style_shipped_id');
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

            $table->foreign('style_shipped_id')
                  ->references('id')
                  ->on('style_shipped')
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
        Schema::drop('style_shipped_style');
    }
}
