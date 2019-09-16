<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_production_order', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->string('se_issuing_office')->nullable();
            $table->string('vendor')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('ship_to')->nullable();
            $table->timestamp('po_date')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('form_user_id')->references('id')->on('form_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_production_order');
    }
}
