<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_developments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('owner');
            $table->boolean('is_parallel');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO style_developments (name, owner, is_parallel, created_at, updated_at) VALUES
                    ('Product Brief gathered', 'Sales Rep', false, now(), now() ),
                    ('Sample fees paid', 'Sales Rep', false, now(), now() ),
                    ('Brand Collateral gathered','Sales Rep', true, now(), now() ),
                    ('Design approved','PD Lead', true, now() , now() ),
                    ('Labelling approved','PD Lead', true, now() , now() ),
                    ('Visual Line Plan approved','PD Lead', true, now() , now() ),
                    ('Fabrics approved','PD Lead', true, now() , now() ),
                    ('Trims approved','PD Lead', true, now() , now() ),
                    ('Lab dips approved','PD Lead', true, now() , now() ),
                    ('Strike-offs approved','PD Lead', true, now() , now() ),
                    ('Fit sample approved','PD Lead', true, now() , now() ),
                    ('Price approved','Sales Rep', true, now() , now() ),
                    ('Customer PO received','Sales Rep', false, now(), now()),
                    ('Customer deposit received','Sales Rep', false, now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('style_developments');
    }
}
