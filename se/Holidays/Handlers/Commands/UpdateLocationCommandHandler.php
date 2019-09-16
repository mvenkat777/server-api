<?php

namespace Platform\Holidays\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Holidays\Repositories\Contracts\LocationRepository;

class UpdateLocationCommandHandler implements CommandHandler 
{
    protected $locationRepository;

	public function __construct(LocationRepository $locationRepository)
	{
        $this->locationRepository = $locationRepository;
	}

	public function handle($command)
	{
        return $this->locationRepository->updateLocation((array)$command, $command->locationId);
	}

}
