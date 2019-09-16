<?php

namespace Platform\App\Commanding;

use Exception;

class CommandTranslator
{
    public function toCommandHandler($command)
    {
        $handler = str_replace('Commands', 'Handlers\Commands', get_class($command)) . 'Handler';
        if (! class_exists($handler)) {
            $message = "Command handler [$handler] does not exisit.";

            throw new Exception($message);
        }
        return $handler;
    }

    public function toValidator($command)
    {
        $validator = str_replace('Commands', 'Validators\Commands', get_class($command). 'Validator');
        if (! class_exists($validator)) {
            return false;
        }
        return $validator;
    }
}
