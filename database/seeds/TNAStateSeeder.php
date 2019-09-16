<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TNAStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement("UPDATE tna_state SET state='draft' WHERE state='unpublished' ");
        \DB::statement("UPDATE tna_state SET state='active' WHERE state='published' ");
        \DB::statement("UPDATE tna_state SET state='completed' WHERE state='archived' ");
    }
}
