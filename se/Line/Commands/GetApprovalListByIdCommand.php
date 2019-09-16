<?php

namespace Platform\Line\Commands;

class GetApprovalListByIdCommand
{
    /**
     * @var string
     */
    public $styleId;

    /**
     * @param string $approvalName
     * @param integer $approvalNameId
     * @param string $styleId
     */
    public function __construct($styleId){
        $this->styleId = $styleId;
    }
}
