<?php

namespace Platform\SampleContainer\Commands;

class DeleteSampleCriteriaByIdCommand
{
    /**
     * @var string
     */
    public $sampleId;

    /**
     * @var string
     */
    public $criteriaId;

    /**
     * Construct the command
     * @param string $sampleId
     * @param string $criteriaId
     */
    public function __construct($sampleId, $criteriaId){
        $this->sampleId = $sampleId;
        $this->criteriaId = $criteriaId;
    }
}