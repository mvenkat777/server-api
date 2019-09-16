<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_library', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('material_id', 100);
            $table->string('vendor_id', 200);
            $table->string('fabric_reference',200)->unique();
            $table->string('fabric_style',100)->nullable();
            $table->decimal('cost_local',10,2)->nullable();
            $table->decimal('cost_usd',10,2)->nullable();
            $table->string('cost_uom')->nullable();
            $table->text('stock',100)->nullable();
            $table->text('avail_greige',100)->nullable();
            $table->text('notes',100)->nullable();
            $table->string('major_customer',100)->nullable();
            $table->string('print_cost',100)->nullable();
            $table->string('fabric_lead_time',100)->nullable();
            $table->integer('minimum_order_quantity')->nullable();
            $table->decimal('minimum_order_quantity_surcharge',10,2)->nullable();
            $table->integer('minimum_color_quantity')->nullable();
            $table->decimal('minimum_color_quantity_surcharge',10,2)->nullable();
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
        Schema::drop('material_library');
    }
}
