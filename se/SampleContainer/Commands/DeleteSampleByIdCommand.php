<?php

namespace Platform\SampleContainer\Commands;

class DeleteSampleByIdCommand
{
    /**
     * @var string
     */
    public $sampleContainerId;

    /**
     * @var string
     */
    public $sampleid;

    /**
     * Construct the command
     * @param string $sampleContainerId
     * @param string $sampleId
     */
    public function __construct($sampleContainerId, $sampleId){
        $this->sampleContainerId = $sampleContainerId;
        $this->sampleId = $sampleId;
    }
}