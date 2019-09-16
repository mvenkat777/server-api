<?php

namespace Platform\App\RuleCommanding;

/**
 * Interface CommandHandler
 * @package Platform\Commands
 */
interface RuleHandler{

    /**
     * @param $command
     * @return mixed
     */
    public function handle($command);

}
