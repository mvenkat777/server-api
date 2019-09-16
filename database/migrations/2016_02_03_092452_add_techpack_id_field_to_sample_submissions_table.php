<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTechpackIdFieldToSampleSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_submissions', function (Blueprint $table) {
            $table->string('techpack_id', 100)->nullable();
            $table->foreign('techpack_id')
                  ->references('id')->on('techpacks')
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
        Schema::table('sample_submissions', function (Blueprint $table) {
            $table->dropForeign('sample_submissions_techpack_id_foreign');
            $table->dropColumn('techpack_id');
        });
    }
}
