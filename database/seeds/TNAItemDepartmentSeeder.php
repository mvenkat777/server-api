<?php


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TNAItemDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('DELETE FROM tna_item_department');
        \DB::insert(" INSERT INTO tna_item_department (id, department, created_at, updated_at) VALUES
                    (1, 'Sales Rep', now() , now() ),
                    (2, 'PD Lead', now() , now() ),
                    (3, 'Sourcing & Production Lead', now() , now() ),
                    (4, 'Regional Merchandiser', now(), now() )");
        /*
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
                    (13, 'Director Finance', now() , now() ),
                    (14, 'Regional Material Manager (ASIA)', now() , now() )");
         */
    }
}
