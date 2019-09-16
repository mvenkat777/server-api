<?php

namespace Platform\Techpacks\Commands;

class ArchiveTechpackCommand 
{
    /**
     * @var string
     */
    public $techpackId;

    /**
     * @param mixed $techpackId
     */
	public function __construct($techpackId){
        $this->techpackId = $techpackId;
	}

}
