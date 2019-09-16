<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO vendor_service (name, created_at, updated_at) VALUES
                    ('FULL PACKAGES', now() , now() ),
                    ('FABRIC', now() , now() ),
                    ('TRIMS', now() , now() ),
                    ('ACCESSORIES', now() , now() ),
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
        Schema::drop('vendor_service');
    }
}
