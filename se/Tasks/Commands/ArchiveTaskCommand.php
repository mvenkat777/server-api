<?php

namespace Platform\Tasks\Commands;

class ArchiveTaskCommand 
{
    public $id;

	public function __construct($id){
        $this->id = $id;
	}

}
