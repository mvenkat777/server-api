<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormUserTable extends Migration
{
    //090538
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_user', function (Blueprint $table) {
            $table->string('id');
            $table->integer('form_name_id');
            $table->integer('form_status_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('submitted_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->string('approval_request_for')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->boolean('is_rejected')->default(false);
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejected_by')->nullable();
            $table->string('remark')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('form_name_id')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('form_status_id')->references('id')->on('form_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_user');
    }
}
