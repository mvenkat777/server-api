<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProductFolderIdFromPicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('picks', function (Blueprint $table) {
            $table->dropColumn('product_folder_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('picks', function (Blueprint $table) {
            $table->uuid('product_folder_id')->nullable();
        });
    }
}
