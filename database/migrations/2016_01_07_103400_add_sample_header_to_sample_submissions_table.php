<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSampleHeaderToSampleSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_submissions', function (Blueprint $table) {
            $table->string('style_code', 30)->default('NA');
            $table->date('sent_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('type', 70)->default('NA');
            $table->string('content', 70)->nullable();
            $table->string('weight', 70)->nullable();
            $table->string('vendor', 70)->nullable();
            $table->string('customer', 70)->default('NA');
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
            $table->dropColumn('style_code');
            $table->dropColumn('sent_date');
            $table->dropColumn('received_date');
            $table->dropColumn('type');
            $table->dropColumn('content');
            $table->dropColumn('weight');
            $table->dropColumn('vendor');
            $table->dropColumn('customer');
        });
    }
}
