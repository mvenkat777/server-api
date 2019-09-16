<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryItemPresetsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_item_presets', function(Blueprint $table)
        {
            $table->string('id',100);
            $table->string('library_item_id',100);
            $table->foreign('library_item_id')->references('id')->on('library_items')->onDelete('cascade');
            $table->string('name',100);
            $table->text('description')->nullable();
            $table->text('data')->nullable();
            $table->boolean('admin_created')->default(false)->nullable();
            $table->enum('status',['active', 'suspended','archived'])->default('active');
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('library_item_presets');
    }

}
