<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCriteriaContraintInSampleCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_criterias', function (Blueprint $table) {
            DB::statement('ALTER TABLE sample_criterias DROP CONSTRAINT sample_criterias_criteria_check'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_criterias', function (Blueprint $table) {
            DB::statement("ALTER TABLE sample_criterias
                ADD CONSTRAINT sample_criterias_criteria_check 
                CHECK (criteria::text = ANY (ARRAY[
                    'fit'::character varying, 
                    'pattern'::character varying, 
                    'measures'::character varying, 
                    'construction'::character varying, 
                    'workmanship'::character varying, 
                    'fabrics and trims'::character varying
                ]::text[]));"
            );
        });
    }
}
