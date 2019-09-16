<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPermissionColumnConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_user', function ($table) {
            $table->dropColumn('permission');
            $table->dropPrimary('role_user_pkey');
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
            $table->enum('permission',['can_read','can_edit','admin'])->default('can_read');
            $table->primary(['role_id','user_id']);
        });
    }
}
