<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("DROP TABLE IF EXISTS vendors");

        Schema::create('vendors', function (Blueprint $table) {
            $table->string('id');
            $table->string('code', 8);
            $table->string('name', 100);
            $table->string('business_entity')->nullable();
            $table->string('import_export_license', 50)->nullable();
            $table->string('tax_id', 50)->nullable();
            $table->string('vat_sales_tax_reg', 50)->nullable();
            $table->string('company_reg', 50)->nullable();
            $table->string('annual_shipped_turnover', 50)->nullable();
            $table->string('annual_shipped_quantity', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendors');
    }
}
