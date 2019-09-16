<?php

use Illuminate\Database\Seeder;

class TNAPresetMigrator extends Seeder
{
	
	function run()
	{
       \DB::statement('CREATE TABLE tna_create_preset (tna_id varchar(255), data json)'); 
    }
}
