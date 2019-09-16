<?php

namespace Platform\TNA\Validators\Commands;

use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\App\Exceptions\SeException;
use Platform\App\Validation\CommandValidator;

class UpdateTNAItemCommandValidator extends CommandValidator
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepo;

    public function __construct(TNAItemRepository $tnaItemRepo)
    {
		$this->tnaItemRepo = $tnaItemRepo;
    }

    public function validate($command)
    {
        if($command->tna->state->state === 'active') {
            /*
            if($command->plannedDate < $command->tna->published_date) {
                throw new SeException('Planned Date cannot be less than TNA Published Date', 422, 4200124);
            }
             */
        } else {
            if($command->plannedDate < $command->tna->start_date) {
                throw new SeException('Planned Date cannot be less than TNA Start Date', 422, 4200124);
            }
        }

        return true;
    }

}

