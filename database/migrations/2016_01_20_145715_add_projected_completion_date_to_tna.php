<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectedCompletionDateToTna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna', function (Blueprint $table) {
            $table->timestamp('projected_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->integer('tna_health_id')->unsigned()->default(1);

            $table->foreign('tna_health_id')->references('id')->on('tna_health')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna', function (Blueprint $table) {
            \DB::statement('ALTER TABLE tna DROP COLUMN projected_date');
            \DB::statement('ALTER TABLE tna DROP COLUMN completed_date');
            \DB::statement('ALTER TABLE tna DROP COLUMN tna_health_id');
        });
    }
}
