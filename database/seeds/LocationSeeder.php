<?php

use Illuminate\Database\Seeder;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Holidays\Commands\AddLocationCommand;


class LocationSeeder extends Seeder
{

	function __construct(
        DefaultCommandBus $commandBus
         
    ) {
        $this->commandBus = $commandBus;
      

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->delete();
        $locations = \Excel::load(base_path().'/resources/assets/SE locations.csv')
        		->get()->toArray();
        //dd($locations);
        foreach ($locations as $key => $location) {	

        	$mapping = [
                'city'=>$location['city'],
                'state'=>$location['state'],
                 'postalCode'=>$location['postal_code'],
                 'address'=>$location['address'],
                    'country'=>$location['country']
                
                    ];
        	$this->commandBus->execute(new AddLocationCommand($mapping));
        }

        dd("Location added successfully");	
    }
}
