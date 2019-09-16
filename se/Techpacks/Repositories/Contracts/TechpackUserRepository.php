<?php

namespace Platform\Techpacks\Repositories\Contracts;

use Platform\Techpacks\Commands\ShareTechpackCommand;

interface TechpackUserRepository
{
    /**
     * @return mixed
     */
    public function model();

    public function share($techpackId, $user);
}
