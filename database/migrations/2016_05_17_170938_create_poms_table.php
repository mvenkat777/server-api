<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poms', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('category_code', 2);
            $table->string('product_type_code', 2);
            $table->string('size_range_name');
            $table->json('size_range_value');
            $table->integer('size_type_id');
            $table->string('base_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('poms');
    }
}
