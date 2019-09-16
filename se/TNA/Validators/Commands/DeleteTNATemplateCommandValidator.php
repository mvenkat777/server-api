<?php

namespace Platform\TNA\Validators\Commands;

use Platform\App\Validation\CommandValidator;

class DeleteTNATemplateCommandValidator extends CommandValidator
{
    
    public function validate($command)
    {
        if(!\Auth::user()->is_god) {
            throw new SeException('You are not authorized to delete tna template', 422);
        }
        return true;
    }

}
