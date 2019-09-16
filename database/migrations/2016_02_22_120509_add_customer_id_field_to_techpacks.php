<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerIdFieldToTechpacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('techpacks', function (Blueprint $table) {
            $table->string('customer_id', 100)->nullable();

			$table->foreign('customer_id')
				  ->references('id')
				  ->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('techpacks', function (Blueprint $table) {
			$table->dropForeign('techpacks_customer_id_foreign');
			$table->dropColumn('customer_id');
        });
    }
}
