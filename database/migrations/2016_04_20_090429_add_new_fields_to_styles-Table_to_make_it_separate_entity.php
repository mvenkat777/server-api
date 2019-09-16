<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToStylesTableToMakeItSeparateEntity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('styles', function (Blueprint $table) {
            DB::statement('ALTER TABLE styles ALTER COLUMN code DROP NOT NULL;');
            $table->text('product_brief')->nullable();
            $table->string('customer_style_code', 70)->nullable();
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
            $table->dropColumn(['product_brief', 'customer_style_code']);
        });
    }
}
