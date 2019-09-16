<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaItemDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_item_department', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->timestamps();
        });

        \DB::insert(" INSERT INTO tna_item_department (id, department, created_at, updated_at) VALUES
                    (1, 'Sales person', now() , now() ),
                    (2, 'VP Product Development', now() , now() ),
                    (3, 'Director Product Development', now() , now() ),
                    (4, 'Product Developer', now() , now() ),
                    (5, 'Designer', now() , now() ),
                    (6, 'Patternmaker', now() , now() ),
                    (7, 'Samplemaker', now() , now() ),
                    (8, 'Materials Coordinator', now() , now() ),
                    (9, 'Sourcing and Production Director (USA)', now() , now() ),
                    (10, 'Sourcing Material Manager (USA)', now() , now() ),
                    (11, 'Quality Role', now() , now() ),
                    (12, 'Regional Merchandiser (ASIA)', now() , now() ),
                    (13, 'Regional Material Manager (ASIA)', now() , now() )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tna_item_department');
    }
}
