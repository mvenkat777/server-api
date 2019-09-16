<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO vendor_types (name, created_at, updated_at) VALUES
                    ('AGENT', now() , now() ),
                    ('FACTORY', now() , now() ),
                    ('TRADER', now() , now() ),
                    ('JOBWORK', now() , now() ),
                    ('SUPPLIER', now() , now() ),
                    ('FABRIC MILL', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendor_types');
    }
}
