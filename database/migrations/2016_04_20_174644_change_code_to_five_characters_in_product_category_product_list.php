<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCodeToFiveCharactersInProductCategoryProductList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_category_product_list', function (Blueprint $table) {
            $table->string('code', 5)->change();
            $table->string('product_list_code', 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_category_product_list', function (Blueprint $table) {
            $table->string('code', 4)->change();
            $table->string('product_list_code', 2)->change();
        });
    }
}
