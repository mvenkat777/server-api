<?php

namespace Platform\Techpacks\Commands;

class UpdateSamplePOMFieldCommand
{
    /**
     * @var object
     */
    public $techpack;

    /**
     * @param object $techpack
     */
	public function __construct($techpack){
        $this->techpack = $techpack;
	}
}