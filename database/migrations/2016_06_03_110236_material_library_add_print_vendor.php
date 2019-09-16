<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MaterialLibraryAddPrintVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_library', function (Blueprint $table) {
            $table->string('primary_print_vendor',100)->nullable();
            $table->string('secondary_print_vendor',100)->nullable();
            \DB::table('material_library')->delete();
            \DB::statement('ALTER TABLE material_library ALTER COLUMN fabric_lead_time type numeric(10,0) using fabric_lead_time::numeric;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_library', function (Blueprint $table) {
            $table->dropColumn('primary_print_vendor');
            $table->dropColumn('secondary_print_vendor');
            \DB::table('material_library')->delete();
            \DB::statement('ALTER TABLE material_library ALTER COLUMN fabric_lead_time type VARCHAR(100) using fabric_lead_time::varchar');
        });
    }
}
