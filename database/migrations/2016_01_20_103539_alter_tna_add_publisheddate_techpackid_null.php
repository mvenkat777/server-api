<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTnaAddPublisheddateTechpackidNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna', function (Blueprint $table) {
            $table->timestamp('published_date')->nullable();

            \DB::statement('ALTER TABLE tna ALTER COLUMN techpack_id DROP NOT NULL;');
            \DB::statement('ALTER TABLE tna ALTER COLUMN customer_id DROP NOT NULL;');
            \DB::statement('ALTER TABLE tna ALTER COLUMN representor_id DROP NOT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna', function (Blueprint $table) {
            \DB::statement('ALTER TABLE tna DROP COLUMN published_date;');
        });
    }
}
