<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_review', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('owner')->nullable();
            $table->boolean('is_parallel');
            $table->timestamps();
        });
        \DB::insert(" INSERT INTO style_review (name, owner, is_parallel, created_at, updated_at) VALUES
                    ('Sales', NULL, true, now() , now() ),
                    ('Design', NULL, true, now() , now() ),
                    ('Production', NULL, true, now() , now() )");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('style_review');
    }
}
