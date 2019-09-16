<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToVendorVendorCapabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $capability = DB::select("select * from vendor_capability");
        $vendors = DB::select("select * from vendors");
        foreach ($vendors as $vendor) {
            foreach ($capability as $cap) {
                $data = DB::select("select * from vendor_vendor_capability 
                                    where vendor_id = '$vendor->id' AND 
                                    vendor_capability_id = $cap->id" );
                if($data == [])
                {
                    DB::statement("INSERT INTO vendor_vendor_capability values(
                            '$vendor->id', $cap->id, false, false, NULL, NULL 
                        ) ");
                }
            }
        }
        Schema::table('vendor_vendor_capability', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_vendor_capability', function (Blueprint $table) {
            //
        });
    }
}
