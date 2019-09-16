<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  
        \DB::statement("DROP TABLE IF EXISTS help");
        Schema::create('help', function (Blueprint $table) {
            $table->string('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->integer('app_id');
            $table->json('author_log')->nullable();
            $table->integer('like');
            $table->integer('dislike');
            $table->json('owner');
            $table->json('feedback')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('app_id')
                    ->references('id')
                    ->on('apps_list')
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
        Schema::drop('help');
    }
}
