<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentIdToTnaItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->integer('department_id')->unsigned()->nullable();

            $table->foreign('department_id')
                  ->references('id')->on('tna_item_department');
        });

        Schema::table('tna_item_presets', function (Blueprint $table) {
            $table->integer('department_id')->unsigned()->nullable();
            $table->boolean('is_current')->default(false)->nullable();

            $table->foreign('department_id')
                  ->references('id')->on('tna_item_department');

        });

        try {
            \DB::statement('ALTER TABLE tna_item_presets DROP COULMN department');
        } catch (Exception $e) {
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->dropForeign('tna_items_department_id_foreign');
            $table->dropColumn('department_id');
        });

        Schema::table('tna_item_presets', function (Blueprint $table) {
            $table->dropForeign('tna_item_presets_department_id_foreign');
            $table->dropColumn('department_id');
            $table->dropColumn('is_current');
        });
    }
}
