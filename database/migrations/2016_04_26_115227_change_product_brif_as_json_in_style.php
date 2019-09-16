<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductBrifAsJsonInStyle extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('styles', function (Blueprint $table) {
            $table->dropColumn(['product_brief']);
        });
        Schema::table('styles', function (Blueprint $table) {
            $table->json('product_brief')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('styles', function (Blueprint $table) {
            $table->dropColumn(['product_brief']);
            $table->text('product_brief')->nullable();
        });
    }
}
