<?php

namespace Platform\App\Commanding;

use Illuminate\Foundation\Application;
use Platform\App\Exceptions\SeException;

class DefaultCommandBus implements CommandBus
{

    private $app;
    protected $commandTranslator;

    function __construct(Application $app, CommandTranslator $commandTranslator)
    {
        $this->app = $app;
        $this->commandTranslator = $commandTranslator;

    }

    public function execute($command)
    {
        $handler = $this->commandTranslator->toCommandHandler($command);
        $validator = $this->commandTranslator->toValidator($command);

        if(class_exists($validator)) {
            if(!$this->app->make($validator)->validate($command)) {
                throw new SeException('Command validation failed', 422, 4220422);
            }
        }
        return $this->app->make($handler)->handle($command);
    }

}
