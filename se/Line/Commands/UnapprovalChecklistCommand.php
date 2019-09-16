<?php

namespace Platform\Line\Commands;

class UnapprovalChecklistCommand 
{
    /**
     * @var string
     */
    public $approvalName;

    /**
     * @var string
     */
    public $styleId;

    /**
     * @var integer
     */
    public $approvalNameId;

    /**
     * @param string $approvalName
     * @param integer $approvalNameId
     * @param string $styleId
     */
    public function __construct($styleId, $approvalName, $approvalNameId){
        $this->approvalName = $approvalName;
        $this->styleId = $styleId;
        $this->approvalNameId = $approvalNameId;
    }
}
