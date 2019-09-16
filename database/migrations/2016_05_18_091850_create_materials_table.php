<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            
            $table->string('id', 100)->primary();
            $table->string('material_reference_no', 20)->unique();
            $table->string('material_type',10)->nullable();
            
            $table->string('construction',10)->nullable();
            $table->string('construction_type',15)->nullable();
            $table->integer('yarn_count')->nullable();
            $table->integer('wheat_count')->nullable();

            $table->string('fabric_type',50)->nullable();

            $table->string('fiber_1',50)->nullable();
            $table->integer('fiber_1_percentage')->nullable();
            $table->string('fiber_2',50)->nullable();
            $table->integer('fiber_2_percentage')->nullable();
            $table->string('fiber_3',50)->nullable();
            $table->integer('fiber_3_percentage')->nullable();

            $table->integer('xfoot_check')->nullable();
            $table->integer('weight')->nullable();
            $table->string('weight_uom',10)->nullable();
            $table->integer('cuttable_width')->nullable();
            $table->string('width_uom',10)->nullable();
            
            $table->softDeletes();
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
        Schema::drop('materials');
    }
}
