<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('code', 15);
            $table->string('customer_id', 100);
            $table->string('sales_representative_id', 100);
            $table->string('production_lead_id', 100);
            $table->datetime('so_target_date');
            $table->datetime('delivery_target_date');
            $table->softDeletes();
            $table->timestamps();
			
            $table->foreign('customer_id')
                      ->references('id')
                      ->on('customers');
            $table->foreign('sales_representative_id')
                      ->references('id')
                      ->on('users');
            $table->foreign('production_lead_id')
                      ->references('id')
                      ->on('users');

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lines');
    }
}
