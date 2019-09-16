<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDouble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('form_sales_order', function (Blueprint $table) {
            
          
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total_discount type double precision using total_discount::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sub_total type double precision using sub_total::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sales_tax type double precision using sales_tax::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total type double precision using total::numeric;');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
