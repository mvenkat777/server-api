<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRolesUniqueAddRoleGroupConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('roles', function (Blueprint $table) {
            \DB::table('roles')->delete();
            $table->dropUnique('roles_name_unique');
            $table->unique(['name','group_id']);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            \DB::table('roles')->delete();
            $table->unique(['name']);
            $table->dropUnique('roles_name_group_id_unique');
        });
    }
}
