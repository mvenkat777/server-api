<?php

namespace Platform\App\Commanding;

interface CommandBus
{

    public function execute ($command);
}
