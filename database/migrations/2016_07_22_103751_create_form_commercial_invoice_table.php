<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormCommercialInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_commercial_invoice', function (Blueprint $table) {
            $table->string('id');
            $table->string('form_user_id');
            $table->text('exporter')->nullable();
            $table->text('consignee')->nullable();
            $table->text('notify_party')->nullable();
            $table->text('loading_port')->nullable();
            $table->text('destination')->nullable();
            $table->text('carrier')->nullable();
            $table->timestamp('sailing_on')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamp('invoice_date')->nullable();
            $table->string('origin_remark')->nullable();
            $table->string('shipment_remark')->nullable();
            $table->string('freight_remark')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('total_package')->nullable();
            $table->string('total_net_weight')->nullable();
            $table->string('total_gross_weight')->nullable();
            $table->string('total_measurement')->nullable();
            $table->json('data')->nullable();
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
        Schema::drop('form_commercial_invoice');
    }
}
