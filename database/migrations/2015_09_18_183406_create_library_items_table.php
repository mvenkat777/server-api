<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryItemsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_items', function(Blueprint $table)
        {
            $table->string('id',100);
            $table->string('name',100)->unique();
            $table->string('slug',100)->unique();
            $table->text('description')->nullable();
            $table->string('email',200)->nullable();
            $table->boolean('admin_created')->default(false)->nullable();
            $table->integer('attribute_count')->default(0)->nullable();
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
        Schema::drop('library_items');
    }

}
