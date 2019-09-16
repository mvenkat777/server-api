<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Platform\TNA\Models\TNA;

class TNAVendorMigrator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tnaList = TNA::all();
        $tnaList->each(function($tna, $key) {
            $vendorId = $tna->vendor_id;
            $tna->vendors()->sync([$vendorId], false);
        });

        \DB::statement('ALTER TABLE tna DROP COLUMN vendor_id');
    }
}
