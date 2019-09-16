<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaItemVisibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_item_visibility', function (Blueprint $table) {
            $table->increments('id');
            $table->string('visibility')->unique();
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO tna_item_visibility (id,visibility, created_at, updated_at) VALUES
                    (1, 'all', now() , now() ),
                    (2, 'customer', now() , now() ),
                    (3, 'vendor', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna_item_visibility');
    }
}
