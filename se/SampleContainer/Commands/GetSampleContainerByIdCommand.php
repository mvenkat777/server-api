<?php

namespace Platform\SampleContainer\Commands;

class GetSampleContainerByIdCommand
{
    /**
     * @var string
     */
    public $sampleContainerId;

    /**
     * Construct the command
     * @param string $sampleContainerId
     */
    public function __construct($sampleContainerId){
        $this->sampleContainerId = $sampleContainerId;
    }
}