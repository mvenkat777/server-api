<?php

namespace Platform\TNA\Commands;

class ArchiveTNACommand 
{
    /**
     * String UUID
     */
    public $tnaId;

	public function __construct($tnaId){
        $this->tnaId = $tnaId;
	}

}
