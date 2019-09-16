<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStyleCodeFieldSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_submissions', function (Blueprint $table) {
            $table->string('style_code', 100)->default('NA')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_submissions', function (Blueprint $table) {
            $table->string('style_code', 30)->default('NA')->change();
        });
    }
}
