<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_production_order', function (Blueprint $table) {
            $table->dropColumn('se_issuing_office');
            $table->dropColumn('vendor');
            $table->dropColumn('customer_id');
        });

        Schema::table('form_production_order', function (Blueprint $table) {
            $table->json('vendor')->nullable();
            $table->json('customer_id')->nullable();
            $table->json('se_issuing_office')->nullable();
            $table->json('total_quantity')->nullable();
            $table->json('total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
