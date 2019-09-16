<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVLPAttachmentApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v_l_p_attachment_approvals', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('line_id', 100);
            $table->string('approver_id', 100);
            $table->boolean('approval');

            $table->primary(['id']);
            $table->foreign('line_id')
                  ->references('id')
                  ->on('lines');
            $table->foreign('approver_id')
                  ->references('id')
                  ->on('users');

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
        Schema::drop('v_l_p_attachment_approvals');
    }
}
