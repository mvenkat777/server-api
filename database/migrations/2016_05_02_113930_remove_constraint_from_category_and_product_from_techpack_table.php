<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveConstraintFromCategoryAndProductFromTechpackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('techpacks', function (Blueprint $table) {
            DB::statement('ALTER TABLE techpacks DROP CONSTRAINT techpacks_product_type_check');
            DB::statement('ALTER TABLE techpacks DROP CONSTRAINT techpacks_product_check');
            DB::statement('ALTER TABLE techpacks DROP CONSTRAINT techpacks_category_check');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('techpacks', function (Blueprint $table) {
            //
        });
    }
}
