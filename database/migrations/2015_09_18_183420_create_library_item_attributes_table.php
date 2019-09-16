<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryItemAttributesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_item_attributes', function(Blueprint $table)
        {
            $table->string('id',100);
            $table->string('library_item_id',100);
            $table->foreign('library_item_id')->references('id')->on('library_items')->onDelete('cascade');
            $table->string('name',100);
            $table->enum('type',['number', 'text','textarea','upload', 'color', 'date', 'datetime', 'email', 'time', 'url', 'tel'])->default('text');
            $table->boolean('multi_valued')->default(false);
            $table->boolean('read_only')->default(false);
            $table->enum('read_access_type',['ADMIN', 'DELEGATED_ADMIN','ALL'])->default('ALL');
            $table->string('slug',100);
            $table->text('description')->nullable();
            $table->boolean('admin_created')->default(false)->nullable();
            $table->integer('value_count')->default(0)->nullable();
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
        Schema::drop('library_item_attributes');
    }

}
