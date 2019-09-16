<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeApprovalsFieldInColorwaysTableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colorways', function (Blueprint $table) {
            DB::statement('ALTER TABLE colorways ALTER COLUMN approval DROP NOT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colorways', function (Blueprint $table) {
            $table->json('approval');
        });
    }
}
