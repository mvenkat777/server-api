<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Techpack;

class MigrateOldCustomersToNewStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$techpacks = Techpack::all();

		foreach ($techpacks as $techpack) {
			if (isset($techpack->meta->customer)) {
				$customer = new stdClass();
				$customer->name = $techpack->meta->customer;
				$meta = ($techpack->meta);
				if (gettype($techpack->meta->customer) != 'object') {
					$meta->customer = $customer;
				}
				$techpack->meta = $meta;
				$techpack->update();
			}
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
