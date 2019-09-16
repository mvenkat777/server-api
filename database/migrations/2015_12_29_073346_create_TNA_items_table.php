<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTNAItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_items', function (Blueprint $table) {
            $table->string('id');
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('tna_id');
            $table->string('creator_id');
            $table->integer('task_days');
            $table->timestamp('planned_date')->nullable();
            $table->timestamp('actual_date')->nullable();
            $table->string('representor_id');
            $table->string('dependor_id')->nullable();
            $table->boolean('is_milestone')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_dispachted')->default(false);
            $table->integer('item_status_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('tna_id')->references('id')->on('tna')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('representor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dependor_id')->references('id')->on('tna_items')->onDelete('cascade');
            $table->foreign('item_status_id')->references('id')->on('task_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna_items');
    }
}
