<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRedesignColumnsToTnaItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->boolean('is_parallel')->default(false);
            $table->string('label')->nullable();
            $table->timestamp('projected_date')->nullable();
            $table->string('delta')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->dropColumn('is_parallel');
            $table->dropColumn('label');
            $table->dropColumn('projected_date');
            $table->dropColumn('delta');
        });
    }
}
