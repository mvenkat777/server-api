<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesLeadIdAndAuthorIdToBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->string('sales_lead_id', 100)->nullable();
            $table->string('author_id', 100)->nullable();

            $table->foreign('sales_lead_id')
                  ->references('id')
                  ->on('users');

            $table->foreign('author_id')
                  ->references('id')
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
        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign('boards_sales_lead_id_foreign');
            $table->dropForeign('boards_author_id_foreign');

            $table->dropColumn(['sales_lead_id', 'author_id']);
        });
    }
}
