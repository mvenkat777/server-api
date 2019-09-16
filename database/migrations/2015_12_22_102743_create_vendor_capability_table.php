<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorCapabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_capability', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO vendor_capability(name, created_at, updated_at) VALUES
                    ('FABRIC PRODN', now() , now() ),
                    ('STOCK FABRIC', now() , now() ),
                    ('TRIM PRODN', now() , now() ),
                    ('STOCK TRIM', now() , now() ),
                    ('ACCESSORIES', now() , now() ),
                    ('CUTTING', now() , now() ),
                    ('SEWING', now() , now() ),
                    ('FINISHING', now() , now() ),
                    ('PACKING', now() , now() ),
                    ('QUALITY CHECK', now() , now() ),
                    ('SAMPLING', now() , now() ),
                    ('EMBROIDERY', now() , now() ),
                    ('PRINTING', now() , now() ),
                    ('SUBLIMATION', now() , now() ),
                    ('DIGITAL PRINT', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendor_capability');
    }
}
