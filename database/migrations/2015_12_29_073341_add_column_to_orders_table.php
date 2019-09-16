<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('TRUNCATE orders cascade');
        Schema::table('orders', function (Blueprint $table) {
            $table->string('code');
            $table->string('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement('Alter Table orders Drop column code');
            DB::statement('Alter Table orders Drop column label');
        });
    }
}
