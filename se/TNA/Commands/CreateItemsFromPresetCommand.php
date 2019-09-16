<?php

namespace Platform\TNA\Commands;

class CreateItemsFromPresetCommand 
{
    public $tnaId;

    public $departments;

	public function __construct($data){
        $this->tnaId = $data['tnaId'];
        $this->departments = $data['departments'];
	}

}
