<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTheImageFieldInSamplesTableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples', function (Blueprint $table) {
            DB::statement('ALTER TABLE samples ALTER COLUMN image DROP NOT NULL;');
            DB::statement('ALTER TABLE samples ALTER COLUMN author_id DROP NOT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('samples', function (Blueprint $table) {
            // $table->json('image');
        });
    }
}
