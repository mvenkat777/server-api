<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLockInTechpack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('techpacks', function (Blueprint $table) {
            $table->timestamp('locked_at')->nullable();
            $table->string('locked_by')->nullable();
            $table->string('unlocked_by')->nullable();
            $table->string('unlocked_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('techpacks', function (Blueprint $table) {
            $table->dropColumn('locked_at');
            $table->dropColumn('locked_by');
            $table->dropColumn('unlocked_by');
            $table->dropColumn('unlocked_at');
        });
    }
}
