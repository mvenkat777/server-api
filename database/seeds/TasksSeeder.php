<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priorities')->delete();
        DB::insert(" INSERT INTO priorities (priority, created_at, updated_at) VALUES
                    ('low', now() , now() ),
                    ('intermediate', now() , now() ),
                    ('highest', now() , now() )
                  ");

        DB::table('task_status')->delete();
        DB::insert(" INSERT INTO task_status (status, created_at, updated_at) VALUES
                    ('unassigned', now() , now() ),
                    ('assigned', now() , now() ),
                    ('started', now() , now() ) ,
                    ('submitted', now() , now() ) ,
                    ('completed', now() , now() ) ,
                    ('closed', now() , now() ) ,
                    ('expired', now() , now() )
                  ");

        DB::table('task_categories')->delete();

        DB::insert("INSERT INTO task_categories (id, title,created_at, updated_at) VALUES
         ('0C2FB096-8S60-4037-BD44-E81590F260A0', 'Sales', now(), now()),
         ('0D3FC096-8A60-4037-DD44-E31590F260A1', 'Samples', now(), now()),
         ('0E4FF096-8Q60-4037-RD44-E41590F260A2', 'Production', now(), now()),
         ('0F5FE096-8R60-4037-GD44-E51590F260A3', 'Development', now(), now()),
         ('0G6FG096-8Y60-4037-AD44-E11590F260A4', 'Customers', now(), now()),
         ('0H7FD096-8G60-4037-RD44-E21590F260A5', 'Operations', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A6', 'Marketing', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A7', 'Costing', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A8', 'Meeting', now(), now()),
         ('0I8FC096-8L60-4036-RE44-E91590F260A9', 'Report', now(), now()),
         ('0I8FC096-8L60-4039-RD44-E91590F260B5', 'Techpack', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260B8', 'Technical', now(), now()),
         ('0I8FC096-8L60-4027-RD44-E91590F260F0', 'Bug', now(), now()),
         ('0I8FC096-8L60-4017-RD44-E91590F260A5', 'Coding', now(), now()),
         ('0I8FC096-8L60-4030-RD44-E91590F260A5', 'Others', now(), now());");

        DB::table('task_assignee_status')->delete();
        \DB::insert(" INSERT INTO task_assignee_status (status, created_at, updated_at) VALUES
                    ('accepted', now() , now() ),
                    ('rejected', now() , now() ),
                    ('snoozed', now() , now() )");
    }
}
