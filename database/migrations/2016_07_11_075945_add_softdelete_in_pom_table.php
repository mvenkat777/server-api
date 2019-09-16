<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeleteInPomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poms', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable()->nullable();
        });
        Schema::table('pom_sheets', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('sizes', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('product_lists', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('size_types', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('archived_at')->nullable();
        });
        Schema::table('size_ranges', function (Blueprint $table) {
            $table->softDeletes();
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
        Schema::table('poms', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('pom_sheets', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('product_lists', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('size_types', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
        Schema::table('size_ranges', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'deleted_at']);
        });
    }
}
