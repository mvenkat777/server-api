<?php

namespace Platform\Techpacks\Commands;

use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class RollbackTechpackCommand 
{
    /**
     * @var string
     */
    public $techpackId;

    /**
     * @param TechpackRepository $techpackRepository
     */
    public function __construct($techpackId) 
    {
        $this->techpackId = $techpackId;
	}

}
