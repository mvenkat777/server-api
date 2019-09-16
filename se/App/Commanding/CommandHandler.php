<?php

namespace Platform\App\Commanding;

/**
 * Interface CommandHandler
 * @package Platform\Commands
 */
interface CommandHandler{

    /**
     * @param $command
     * @return mixed
     */
    public function handle($command);

}
