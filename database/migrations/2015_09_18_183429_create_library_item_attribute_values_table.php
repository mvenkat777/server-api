<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryItemAttributeValuesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_item_attribute_values', function(Blueprint $table)
        {
            $table->string('id',100);
            $table->string('library_item_id',100);
            $table->foreign('library_item_id')->references('id')->on('library_items')->onDelete('cascade');
            $table->string('library_item_attribute_id',100);
            $table->foreign('library_item_attribute_id')->references('id')->on('library_item_attributes')->onDelete('cascade');
            $table->string('value',100);
            $table->boolean('admin_created')->default(false)->nullable();
            $table->enum('status',['active', 'suspended','archived'])->default('active');
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->primary('id');
//            $table->primary(['library_item_attribute_id', 'value'],'attribute_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('library_item_attribute_values');
    }

}
