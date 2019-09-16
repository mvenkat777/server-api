<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSizeRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 3);
            $table->string('range');
            $table->integer('size_type_id');
            $table->json('range_value')->nullable();
            $table->timestamps();

            $table->foreign('size_type_id')
                    ->references('id')
                    ->on('size_types')
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
        Schema::drop('size_ranges');
    }
}
