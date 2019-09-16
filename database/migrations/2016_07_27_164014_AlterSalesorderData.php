<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSalesorderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('form_sales_order', function (Blueprint $table) {
          
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total_discount type numeric(10,10) using total_discount::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sub_total type numeric(10,10) using sub_total::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sales_tax type numeric(10,10) using sales_tax::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total type numeric(10,10) using total::numeric;');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total_discount type numeric(10,5) using total_discount::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sub_total type numeric(10,5) using sub_total::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sales_tax type numeric(10,5) using sales_tax::numeric;');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total type numeric(10,5) using total::numeric;');
    }
}
