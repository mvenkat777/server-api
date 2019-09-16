<?php

namespace Platform\Holidays\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Holidays\Repositories\Contracts\LocationRepository;
use Platform\Holidays\Repositories\Contracts\HolidayRepository;

class DeleteLocationCommandHandler implements CommandHandler 
{
    /**
     * @var Platform\Holidays\Repositories\Contracts\LocationRepository
     */
    protected $locationRepository;

    /**
     * @var Platform\Holidays\Repositories\Contracts\HolidayRepository
     */
    protected $holidayRepository;

	public function __construct(LocationRepository $locationRepository, HolidayRepository $holidayRepository)
	{
        $this->locationRepository = $locationRepository;
        $this->holidayRepository = $holidayRepository;
	}

	public function handle($command)
	{
        $result = $this->locationRepository->deleteLocation($command->locationId);
        if($result) {
            $this->holidayRepository->deleteByLocationId($command->locationId);
        }
        return $result;
	}

}
