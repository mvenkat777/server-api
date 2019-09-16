<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSalesOrderForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->string('tax_id_number')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_discount',10,5)->nullable();
            $table->decimal('sub_total',10,5)->nullable();
            $table->decimal('sales_tax',10,5)->nullable();
            $table->decimal('total',10,5)->nullable();
           

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_sales_order', function (Blueprint $table) {
            $table->dropColumn('tax_id_number');
            $table->dropColumn('notes');
            $table->dropColumn('total_discount');
            $table->dropColumn('sub_total');
            $table->dropColumn('sales_tax');
            $table->dropColumn('total');

     });
    }
}
