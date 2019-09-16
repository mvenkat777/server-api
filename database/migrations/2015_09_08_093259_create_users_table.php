<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('display_name', 70)->nullable();
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->smallInteger('reset_pin')->default(0);
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_god')->default(false);
            $table->boolean('se')->default(false);
            $table->boolean('is_active')->default(false);
            $table->string('confirmation_code')->nullable();
            $table->json('last_login_location')->nullable();
            $table->boolean('is_password_change_required')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
