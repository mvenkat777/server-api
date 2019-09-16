<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchivedAtInLineAndStyle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lines', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('styles', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lines', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
        Schema::table('styles', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
    }
}
