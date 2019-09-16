<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SizeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('size_types')->delete();
        \DB::insert(" INSERT INTO size_types (size_type, created_at, updated_at) VALUES
                    ('ALPHA', now() , now() ),
                    ('NUMERIC', now() , now() ),
                    ('ALFANUM', now() , now() )");
    }
}
