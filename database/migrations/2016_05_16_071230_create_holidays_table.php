<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->string('id');
            $table->timestamp('date');
            $table->string('day');
            $table->boolean('is_work_day')->default(true);
            $table->text('description')->nullable();
            $table->text('affected_supply_chain')->nullable();
            $table->string('location_id');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('location_id')
                    ->references('id')
                    ->on('locations')
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
        Schema::drop('holidays');
    }
}
