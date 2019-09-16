<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesLeadIdToCollabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collabs', function (Blueprint $table) {
            $table->string('sales_lead_id', 100)->nullable();

            $table->foreign('sales_lead_id')->references('id')
                                            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collabs', function (Blueprint $table) {
            $table->dropColumn('sales_lead_id');
        });
    }
}
