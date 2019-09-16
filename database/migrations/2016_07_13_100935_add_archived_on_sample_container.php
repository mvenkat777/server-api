<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchivedOnSampleContainer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_containers', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('samples', function (Blueprint $table) {
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
        Schema::table('sample_containers', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
    }
}
