<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTnaTableChangeSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna', function (Blueprint $table) {
            // $table->string('order_id', 50)->nullable()->change();
            // $table->string('order_quantity', 50)->nullable()->change();
            // $table->dropColumn('tna_type_id');

            \DB::statement('ALTER TABLE tna ALTER COLUMN order_id DROP NOT NULL;');
            \DB::statement('ALTER TABLE tna ALTER COLUMN order_quantity DROP NOT NULL;');
            \DB::statement('ALTER TABLE tna ALTER COLUMN customer_name DROP NOT NULL;');
            \DB::statement('ALTER TABLE tna ALTER COLUMN customer_code DROP NOT NULL;');
            
            \DB::statement('ALTER TABLE tna DROP COLUMN tna_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna', function (Blueprint $table) {
            $table->integer('tna_type_id')->unsigned()->default(1);
            $table->foreign('tna_type_id')->references('id')->on('tna_type')->onDelete('cascade');
        });
    }
}
