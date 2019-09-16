<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;

class UpdateTNACommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @param TNARepository       $tnaRepository       
	 * @param TNAHealthCalculator $tnaHealthCalculator 
	 */
	public function __construct(
		TNARepository $tnaRepository 
	)
	{
		$this->tnaRepository = $tnaRepository;
	}

	/**
	 * @param  UpdateTNACommand $command 
	 * @return TNA          
	 */
	public function handle($command)
	{
		$tna = $this->tnaRepository->updateTNA((array)$command);
        $tna = (new TNAProjectedDateCalculator)->calculate($tna);

		if($tna)
			return $tna;
		else
			throw new SeException("Check tna Id and data", 422, 4200102);
	}

}
