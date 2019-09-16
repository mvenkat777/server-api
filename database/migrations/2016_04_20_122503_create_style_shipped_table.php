<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleShippedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_shipped', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('owner');
            $table->boolean('is_parallel');
            $table->timestamps();
        });
        \DB::insert(" INSERT INTO style_shipped (name, owner, is_parallel, created_at, updated_at) VALUES
                    ('Customer Payment received', 'Sales Rep', true, now() , now() ),
                    ('Vendor Payment issued', 'Sourcing & Production Lead', true, now() , now() ),
                    ('Receipt of Goods signed', 'Sales Rep', true, now() , now() ),
                    ('Acceptance of Goods signed', 'Sales Rep', false, now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('style_shipped');
    }
}
