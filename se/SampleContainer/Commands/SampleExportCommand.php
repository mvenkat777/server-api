<?php

namespace Platform\SampleContainer\Commands;

class SampleExportCommand
{
    /**
     * @var string
     */
    public $sampleContainerId;

    /**
     * @var string
     */
    public $sampleId;

    /**
     * @var string
     */
    public $isEmail;

    /**
     * Constructing the command
     */
	public function __construct($sampleContainerId, $sampleId, $request = []){
        $this->sampleContainerId = $sampleContainerId;
        $this->sampleId = $sampleId;
        $this->isEmail = isset($request['isEmail'])? $request['isEmail'] : false;
	}
}
