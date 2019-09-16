<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsForGlobalAppFilterInAppLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apps_list', function (Blueprint $table) {
            $table->string('url', 255)->nullable();
            $table->boolean('is_searchable')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apps_list', function (Blueprint $table) {
            $table->dropColumn(['url', 'isSearchable']);
        });
    }
}
