<?php

namespace Platform\TNA\Validators\Commands;

use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\App\Exceptions\SeException;
use Platform\App\Validation\CommandValidator;

class PublishTnaItemCommandValidator extends CommandValidator
{
    
    public function validate($command)
    {

        return true;
    }

}
