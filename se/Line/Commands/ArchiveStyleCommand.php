<?php

namespace Platform\Line\Commands;

class ArchiveStyleCommand 
{
    /**
     * @var string
     */
    public $lineId;

    /**
     * @var string
     */
    public $styleId;

    /**
     * @param string $lineId
     * @param string $styleId
     */
    public function __construct($lineId, $styleId){
        $this->lineId = $lineId;
        $this->styleId = $styleId;
    }
}
