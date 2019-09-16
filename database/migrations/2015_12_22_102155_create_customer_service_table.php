<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO customer_service (name, created_at, updated_at) VALUES
                    ('LINE DEVELOPMENT', now() , now() ),
                    ('SMALL RUN PRODN', now() , now() ),
                    ('SEASONAL PRODN', now() , now() ),
                    ('BRAND DEV', now() , now() ),
                    ('CMT ONLY', now() , now() ),
                    ('PRINTING', now() , now() ),
                    ('EMBROIDERY', now() , now() ),
                    ('SUBLIMATION', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_service');
    }
}
