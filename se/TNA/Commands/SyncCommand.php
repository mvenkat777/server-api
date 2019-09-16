<?php

namespace Platform\TNA\Commands;

class SyncCommand 
{
	public $tnaId;

	public $itemsOrder;

    public $tna;

	public function __construct($data, $tnaId, $tna = null){
		$this->tnaId = $tnaId;
		$this->itemsOrder = $data;
        $this->tna = $tna;
	}

}
