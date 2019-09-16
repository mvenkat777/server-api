<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->enum('permission', ['admin','can_read','can_edit'])->default('can_read');
        });
        Schema::table('group_user', function (Blueprint $table) {
            $table->dropColumn(array('permission'));
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
            $table->dropIfExists(array('permission'));
        });
    }
}
