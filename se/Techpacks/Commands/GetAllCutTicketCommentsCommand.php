<?php

namespace Platform\Techpacks\Commands;

class GetAllCutTicketCommentsCommand 
{
    /**
     * @var string
     */
    public $techpacksId;

    /**
     * @param string $techpackId
     */
    public function __construct($techpackId) {
        $this->techpackId = $techpackId;
    }
}
