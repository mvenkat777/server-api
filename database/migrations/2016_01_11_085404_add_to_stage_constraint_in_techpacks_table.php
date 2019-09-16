<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToStageConstraintInTechpacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('techpacks', function (Blueprint $table) {
            DB::statement('ALTER TABLE techpacks DROP CONSTRAINT techpacks_stage_check');
            DB::statement("ALTER TABLE techpacks
                ADD CONSTRAINT techpacks_stage_check CHECK (stage::text = ANY (ARRAY['pp_sample'::character varying::text, 'costing'::character varying::text, 'proto'::character varying::text, 'production'::character varying::text, 'design'::character varying::text, ''::character varying::text]))
            ");
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
            DB::statement('ALTER TABLE techpacks DROP CONSTRAINT techpacks_stage_check');
            DB::statement("ALTER TABLE techpacks
                ADD CONSTRAINT techpacks_stage_check CHECK (stage::text = ANY (ARRAY['pp_Sample'::character varying::text, 'costing'::character varying::text, 'proto'::character varying::text, 'production'::character varying::text, ''::character varying::text]))
            ");
        });
    }
}
