<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO customer_types (name, created_at, updated_at) VALUES
                    ('BRAND', now() , now() ),
                    ('DESIGNER', now() , now() ),
                    ('RETAILER', now() , now() ),
                    ('ECOMMERCE', now() , now() ),
                    ('LICENSING', now() , now() ),
                    ('UNIFORMS', now() , now() ),
                    ('STARTUP', now() , now() ),
                    ('PROMOTIONAL', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_types');
    }
}
