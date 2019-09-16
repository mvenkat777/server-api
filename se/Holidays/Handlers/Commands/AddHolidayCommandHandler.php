<?php

namespace Platform\Holidays\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Holidays\Repositories\Contracts\HolidayRepository;

class AddHolidayCommandHandler implements CommandHandler 
{
    protected $holidayRepository;

	public function __construct(HolidayRepository $holidayRepository)
	{
        $this->holidayRepository = $holidayRepository;
	}

	public function handle($command)
	{
        return $this->holidayRepository->add((array)$command);
	}

}
