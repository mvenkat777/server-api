<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePomSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pom_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pom_id');
            $table->boolean('key')->default(false);
            $table->boolean('qc')->default(false);
            $table->string('code');
            $table->text('description')->nullable();
            $table->text('tol')->nullable();
            $table->json('data');
            $table->timestamps();

            $table->foreign('pom_id')
                    ->references('id')
                    ->on('poms')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pom_sheets');
    }
}
