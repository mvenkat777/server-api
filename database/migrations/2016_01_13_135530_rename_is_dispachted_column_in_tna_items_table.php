<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameIsDispachtedColumnInTnaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->renameColumn('is_dispachted', 'is_dispatched');
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
            $table->renameColumn('is_dispatched', 'is_dispachted');
        });
    }
}
