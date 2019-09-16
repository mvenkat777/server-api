<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_payments', function (Blueprint $table) {
            $table->string('id');
            $table->string('email');
            $table->string('name');
            $table->string('product_name');
            $table->string('description')->nullable();
            $table->string('amount');
            $table->string('product_link');
            $table->string('status', 20)->nullable()->default('pending');
            $table->text('user_object')->nullable();
            $table->text('user_location')->nullable();
            $table->text('sender_name')->nullable();
            $table->text('sender_email')->nullable();
            $table->boolean('payment_status')->default(0);
            $table->text('upload_link_object')->nullable();

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
        Schema::drop('general_payments');
    }
}
