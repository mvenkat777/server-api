<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_productions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('owner');
            $table->boolean('is_parallel');
            $table->timestamps();
        });
        \DB::insert(" INSERT INTO style_productions (name, owner, is_parallel, created_at, updated_at) VALUES
                    ('Vendor PO issued', 'Sourcing & Production Lead', false, now() , now() ),
                    ('Vendor deposit paid', 'Sourcing & Production Lead', true, now() , now() ),
                    ('Production plan set', 'Sourcing & Production Lead', true, now() , now() ),
                    ('PP approved', 'Sourcing & Production Lead', false,  now(), now() ),
                    ('Production in progress', 'Sourcing & Production Lead', false, now() , now() ),
                    ('TOP approved', 'Sourcing & Production Lead', true, now(), now() ),
                    ('QA approved to ship', 'Sourcing & Production Lead', true, now(), now() ),
                    ('Goods shipped', 'Sourcing & Production Lead', false, now(), now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('style_productions');
    }
}
