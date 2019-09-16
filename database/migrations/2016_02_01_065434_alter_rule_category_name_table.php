<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRuleCategoryNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_rules', function (Blueprint $table) {
            $table->dropForeign('category_rules_category_id_foreign');
        });

        Schema::table('category_custom_rules', function (Blueprint $table) {
            $table->dropForeign('category_custom_rules_foreign_category_id_foreign');
        });

        DB::statement('ALTER TABLE category_rules ALTER COLUMN 
            category_id TYPE VARCHAR(100)');
        DB::statement('ALTER TABLE category_rules ALTER COLUMN 
            id TYPE VARCHAR(100)');

        DB::statement('ALTER TABLE category_custom_rules ALTER COLUMN 
            foreign_category_id TYPE VARCHAR(100)');
        DB::statement('ALTER TABLE category_custom_rules ALTER COLUMN 
            id TYPE VARCHAR(100)');

        DB::statement('ALTER TABLE rules_category_name ALTER COLUMN 
            id TYPE VARCHAR(100)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rules_category_name', function (Blueprint $table) {
            //
        });
    }
}
