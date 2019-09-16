<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleAppsPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_app_perms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_app_id')->unsigned();
            $table->foreign('role_app_id')->references('id')->on('role_app')->onDelete('cascade');
            $table->integer('perm_id')->unsigned();
            $table->foreign('perm_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->unique(['role_app_id','perm_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_app_perms');
    }
}
