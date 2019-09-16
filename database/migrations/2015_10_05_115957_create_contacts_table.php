<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("DROP TABLE IF EXISTS contacts");

        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->string('mobile_number1')->nullable();
            $table->string('mobile_number2')->nullable();
            $table->string('mobile_number3')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('skype_id')->nullable();
            $table->string('designation')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
