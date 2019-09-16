<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

         \DB::insert(" INSERT INTO customer_requirements(name, created_at, updated_at) VALUES
                    ('COMPLIANCE', now() , now() ),
                    ('CERTIFICATION', now() , now() ),
                    ('FREIGHT/DUTIES', now() , now() ),
                    ('DISTRIBUTION', now() , now() ),
                    ('SPECIAL PACKING', now() , now() ),
                    ('LICENSING', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_requirements');
    }
}
