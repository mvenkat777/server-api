<?php

namespace Platform\Customer\Commands;

class AddSalesRepresentativeToCollabCommand 
{
    /**
     * @var string
     */
    public $collabId;

    /**
     * @var string
     */
    public $salesRepresentativeId;

    /**
     * @param string $collabId
     * @param string $salesRepresentativeId
     */
	public function __construct($collabId, $salesRepresentativeId){
        $this->collabId = $collabId;
        $this->salesRepresentativeId = $salesRepresentativeId;
	}
}
