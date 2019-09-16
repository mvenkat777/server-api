<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_state', function (Blueprint $table) {
            $table->increments('id');
            $table->string('state')->unique();
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO tna_state (id, state, created_at, updated_at) VALUES
                    (1, 'draft', now() , now() ),
                    (2, 'active', now() , now() ),
                    (3, 'paused', now() , now() ),
                    (4, 'completed', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna_state');
    }
}
