<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleEngineCategoryRulesMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')
                    ->unsigned();
            $table->foreign('category_id')
                    ->references('id')
                    ->on('rules_category_name')
                    ->onDelete('cascade');
            $table->string('rule_name',255);
            $table->text('rule_description')
                    ->nullable();
            $table->json('default_rule')
                    ->nullable();
            $table->softDeletes();
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
        Schema::drop('category_rules');
    }
}
