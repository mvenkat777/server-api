<?php

use Illuminate\Database\Seeder;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Holidays\Commands\AddHolidayCommand ;
use Platform\Holidays\Commands\AddLocationCommand;
use Platform\Holidays\Repositories\Contracts\LocationRepository;
use Platform\Holidays\Repositories\Contracts\HolidayRepository;

use App\Holidays;
use Platform\Holidays\Models\Location;

class HolidaysSeeder extends Seeder
{

	function __construct(DefaultCommandBus $commandBus,
        LocationRepository $locationRepository,
        HolidayRepository $holidayRepository 
    ) {
		$this->commandBus = $commandBus;
        $this->locationRepository = $locationRepository;
        $this->holidayRepository = $holidayRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('holidays')->delete();

        $holidays = \Excel::load(base_path().'/resources/assets/NEWHolidays.csv')
        		->get()->toArray();

        $locs = Location::lists('id','city');

        foreach ($holidays as $key => $location) { 
               $data=[
                   'date'=> $location['date'],
                   'weekDay'=>$location['day_of_week'],
                   'isWorkDay'=>($location['affect'] === 'Work Day')? true : false,
                   'affectedSupplyChain'=>$location['affected_supply_chain'],
                   'description'=>$location['description'],
                   'year' => (int) '20'. trim(explode('/', $location['date'])[2], '20')
               ];

              $newHolidays = $this->commandBus->execute(new AddHolidayCommand($data, $locs[$location['country']]));
        }

        echo 'Holidays created';
    }
}
