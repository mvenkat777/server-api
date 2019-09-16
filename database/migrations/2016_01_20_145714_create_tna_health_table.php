<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaHealthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_health', function (Blueprint $table) {
            $table->increments('id');
            $table->string('health');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO tna_health (id, health, created_at, updated_at) VALUES
                    (1, 'normal', now() , now() ),
                    (2, 'warning', now() , now() ),
                    (3, 'danger', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna_health');
    }
}
