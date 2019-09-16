<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleEngineCategoryCustomRulesMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_custom_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('foreign_category_id')
                    ->unsigned();
            $table->foreign('foreign_category_id')
                    ->references('id')
                    ->on('category_rules')
                    ->onDelete('cascade');
            $table->string('custom_rule_name',255)
                    ->nullable();
            $table->text('custom_rule_description')
                    ->nullable();
            $table->json('custom_rule')
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
        Schema::drop('category_custom_rules');
    }
}
