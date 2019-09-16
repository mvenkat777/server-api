<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCompletedFieldInAllApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lines', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
        }); 
        Schema::table('styles', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
        }); 
        Schema::table('techpacks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
        }); 
        Schema::table('sample_containers', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
        }); 
        Schema::table('samples', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
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
            $table->dropColumn(['completed_at']);
        });
        Schema::table('styles', function (Blueprint $table) {
            $table->dropColumn(['completed_at']);
        });
        Schema::table('techpacks', function (Blueprint $table) {
            $table->dropColumn(['completed_at']);
        });
        Schema::table('sample_containers', function (Blueprint $table) {
            $table->dropColumn(['completed_at']);
        });
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn(['completed_at']);
        });
    }
}
