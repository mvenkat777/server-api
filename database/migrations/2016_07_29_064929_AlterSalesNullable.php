<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSalesNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('form_sales_order', function (Blueprint $table) {
            
          
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total_discount type double precision using total_discount::numeric , ALTER COLUMN total_discount DROP NOT NULL');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sub_total type double precision using sub_total::numeric , ALTER COLUMN sub_total DROP NOT NULL');
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN sales_tax type double precision using sales_tax::numeric , ALTER COLUMN sales_tax DROP NOT NULL');
            
            \DB::statement('ALTER TABLE form_sales_order ALTER COLUMN total type double precision using total::numeric, ALTER COLUMN total  DROP NOT NULL');

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
