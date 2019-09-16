<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_criterias', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('sample_id', 100);
            $table->enum('criteria', [
                'fit', 'pattern', 'measures', 'construction', 'workmanship',
                'fabrics and trims',
            ]);
            $table->text('description')->nullable();

            $table->foreign('sample_id')
                  ->references('id')
                  ->on('samples')
                  ->onDelete('cascade');
            $table->primary(['id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sample_criterias');
    }
}
