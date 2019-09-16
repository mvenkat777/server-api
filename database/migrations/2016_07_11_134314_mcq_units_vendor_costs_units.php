<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class McqUnitsVendorCostsUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_library', function (Blueprint $table) {
            $table->string('minimum_order_quantity_uom',10)->nullable();
            $table->string('minimum_color_quantity_uom',10)->nullable();
            $table->decimal('minimum_order_quantity_surcharge_usd',10,2)->nullable();
            $table->decimal('minimum_color_quantity_surcharge_usd',10,2)->nullable();

            $table->dropColumn('print_cost');
            
            $table->decimal('primary_print_vendor_cost_local',10,2)->nullable();
            $table->decimal('primary_print_vendor_cost_usd',10,2)->nullable();
            $table->string('primary_print_vendor_cost_uom',10)->nullable();

            $table->decimal('secondary_print_vendor_cost_local',10,2)->nullable();
            $table->decimal('secondary_print_vendor_cost_usd',10,2)->nullable();
            $table->string('secondary_print_vendor_cost_uom',10)->nullable();

            
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
            $table->dropColumn('minimum_order_quantity_uom');
            $table->dropColumn('minimum_color_quantity_uom');
            $table->dropColumn('minimum_order_quantity_surcharge_usd');
            $table->dropColumn('minimum_color_quantity_surcharge_usd');

            $table->string('print_cost',100)->nullable();

            $table->dropColumn('primary_print_vendor_cost_local');
            $table->dropColumn('primary_print_vendor_cost_usd');
            $table->dropColumn('primary_print_vendor_cost_uom');

            $table->dropColumn('secondary_print_vendor_cost_local');
            $table->dropColumn('secondary_print_vendor_cost_usd');
            $table->dropColumn('secondary_print_vendor_cost_uom');

        });
    }
}
