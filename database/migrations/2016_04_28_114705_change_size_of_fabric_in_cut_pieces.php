<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizeOfFabricInCutPieces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cut_pieces', function (Blueprint $table) {
            \DB::statement('ALTER TABLE cut_pieces ALTER COLUMN fabric TYPE varchar(100)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cut_pieces', function (Blueprint $table) {
            \DB::statement('ALTER TABLE cut_pieces ALTER COLUMN fabric TYPE varchar(4)');
        });
    }
}
