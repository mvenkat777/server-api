<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TaskAssigneeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::insert(" INSERT INTO `taskAssigneeStatus` (`status`, `created_at`, `updated_at`) VALUES 
                    ('accepted', now() , now() ), 
                    ('rejected', now() , now() ),
                    ('snoozed', now() , now() )");
    }
}
