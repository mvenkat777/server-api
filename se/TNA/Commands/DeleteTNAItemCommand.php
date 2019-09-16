<?php

namespace Platform\TNA\Commands;

class DeleteTNAItemCommand 
{
	/**
	 * @var string UUID
	 */
	public $itemId;

    public $tna;

    public $tnaItem;

	public function __construct($id, $tna, $tnaItem){
		$this->itemId = $id;
        $this->tna = $tna;
        $this->tnaItem = $tnaItem;
	}

}
