<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_purchase_order', function (Blueprint $table) {
            $table->string('po')->nullable();
        });
        Schema::table('form_shipping_notice', function (Blueprint $table) {
            $table->timestamp('created_by_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_purchase_order', function (Blueprint $table) {
            $table->dropColumn('po');
        });
        Schema::table('form_shipping_notice', function (Blueprint $table) {
            $table->dropColumn('created_by_date');
        });
        
    }
}
