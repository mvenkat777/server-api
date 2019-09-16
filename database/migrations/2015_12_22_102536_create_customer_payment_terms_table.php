<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO customer_payment_terms(name, created_at, updated_at) VALUES
                    ('L/C', now() , now() ),
                    ('T/T', now() , now() ),
                    ('D/P', now() , now() ),
                    ('D/A', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_payment_terms');
    }
}
