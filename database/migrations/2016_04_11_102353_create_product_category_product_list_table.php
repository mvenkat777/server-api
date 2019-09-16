<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoryProductListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_product_list', function (Blueprint $table) {
            $table->string('product_category_code', 2);
            $table->string('product_list_code', 2);
            $table->string('code', 5);

            $table->foreign('product_category_code')
                  ->references('code')
                  ->on('product_categories')
                  ->onDelete('cascade');

            $table->foreign('product_list_code')
                  ->references('code')
                  ->on('product_lists')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_category_product_list');
    }
}
