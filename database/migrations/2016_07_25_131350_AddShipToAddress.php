<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipToAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('form_sales_order', function (Blueprint $table) {
            $table->string('ship_to_address')->nullable();
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
             $table->dropColumn('ship_to_address');
        }); 
    }
}
