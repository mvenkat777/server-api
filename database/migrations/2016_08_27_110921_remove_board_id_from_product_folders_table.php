<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBoardIdFromProductFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_folders', function (Blueprint $table) {
            $table->dropColumn('board_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_folders', function (Blueprint $table) {
            $table->uuid('board_id')->nullable();
        });
    }
}
