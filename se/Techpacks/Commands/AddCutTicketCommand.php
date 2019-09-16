<?php

namespace Platform\Techpacks\Commands;

class AddCutTicketCommand 
{
    /**
     * @var string
     */
    public $techpackId;

    /**
     * @var array
     */
    public $billOfMaterials;

    /**
     * @var string
     */
    public $cutTickets;

    public function __construct($techpackId, $billOfMaterials, $cutTickets){ 
        $this->techpackId = $techpackId;
        $this->billOfMaterials = $billOfMaterials;
        $this->cutTickets = $cutTickets;
    }
}
