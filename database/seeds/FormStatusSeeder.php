<?php

use Illuminate\Database\Seeder;

class FormStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   \DB::statement("TRUNCATE TABLE form_status RESTART IDENTITY CASCADE");
         \DB::insert(" INSERT INTO form_status (status, is_editable,created_at, updated_at) VALUES 

                    ('pending',true, now() , now() ),
                     ('submitted',false, now() , now() ),
                    ('rejected',true, now() , now() ),
                    ('approved',false, now() , now() )");
         echo 'Form status seeder executed';
    }
}
