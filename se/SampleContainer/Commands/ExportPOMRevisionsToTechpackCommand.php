<?php

namespace Platform\SampleContainer\Commands;

class ExportPOMRevisionsToTechpackCommand
{
    /**
     * [$sampleContainerId description]
     * @var [type]
     */
    public $sampleContainerId;

    /**
     * [$sampleId description]
     * @var [type]
     */
    public $sampleId;

    /**
     * @param string $sampleContainerId
     * @param string $sampleId
     */
	public function __construct($sampleContainerId, $sampleId){
        $this->sampleContainerId = $sampleContainerId;
        $this->sampleId = $sampleId;
	}

}