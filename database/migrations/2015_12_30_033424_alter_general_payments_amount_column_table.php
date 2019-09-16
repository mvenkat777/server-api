<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// use DB;

class AlterGeneralPaymentsAmountColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_payments', function(Blueprint $table)
        {
            $table->string('status',255)->change();
            DB::statement('ALTER TABLE general_payments ALTER COLUMN amount TYPE double precision USING amount::double precision');
            // DB::statement('update general_payments set amount::integer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_payments', function(Blueprint $table)
        {
            $table->string('status',20)->change();
            DB::statement('ALTER TABLE general_payments ALTER COLUMN amount TYPE varchar USING amount::varchar');
        });
    }
}
