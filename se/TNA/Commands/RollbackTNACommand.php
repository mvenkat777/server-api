<?php

namespace Platform\TNA\Commands;

class RollbackTNACommand 
{
    /**
     * @var String UUID
     */
    public $tnaId;

	public function __construct($tnaId){
        $this->tnaId = $tnaId;
	}

}
