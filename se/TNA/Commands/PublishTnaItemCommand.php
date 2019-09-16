<?php

namespace Platform\TNA\Commands;

class PublishTnaItemCommand 
{
    public $itemId;

    public $tna;
    
    public $tnaItem;
    
    public function __construct($itemId, $tna, $tnaItem){
        $this->itemId = $itemId;
        $this->tna = $tna;
        $this->tnaItem = $tnaItem;
	}

}
