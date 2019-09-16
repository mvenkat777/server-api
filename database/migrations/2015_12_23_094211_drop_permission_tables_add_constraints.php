<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPermissionTablesAddConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('role_app_perms');
        Schema::dropIfExists('role_app');
        Schema::dropIfExists('group_user');
        Schema::table('role_user', function (Blueprint $table) {
            \DB::table('role_user')->truncate();
            $table->primary(['role_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            \DB::table('role_user')->truncate();
            $table->dropPrimary('role_user_pkey');
        });
    }
}
