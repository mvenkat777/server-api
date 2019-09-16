<?php

namespace Platform\TNA\Validators\Commands;

use Platform\App\Exceptions\SeException;
use Platform\App\Validation\CommandValidator;

class CreateTNAItemCommandValidator extends CommandValidator
{

    public function validate($command)
    {
		// if($this->isLessThanToday($command->plannedDate)){
		// 	throw new SeException("Planned date is less than today.", 422, 4200101);
		// }

		if($command->tna->state->state === 'completed'){
			throw new SeException('You cannot add task to completed TNA', 422, 4200119);
		}

		if($command->plannedDate < $command->tna->start_date) {
			throw new SeException('Planned date is less than TNA start date', 422, 4200120);
		}

        return true;
    }

}
