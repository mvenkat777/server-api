<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_library_customers', function (Blueprint $table) {
            $table->string('library_id', 200);
            $table->string('customer_id', 200);
            $table->primary(['library_id','customer_id']);
            $table->foreign('library_id')->references('id')->on('material_library')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('material_library_customers');
    }
}
