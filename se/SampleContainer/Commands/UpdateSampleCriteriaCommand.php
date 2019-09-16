<?php

namespace Platform\SampleContainer\Commands;

class UpdateSampleCriteriaCommand
{
    /**
     * The id of the sample
     * @var string
     */
    public $sampleId;

    /**
     * The criteria id
     * @var string
     */
    public $criteriaId;

    /**
     * The description
     * @var string
     */
    public $description;

    /**
     * The note
     * @var string
     */
    public $note;

    /**
     * Constructing the command
     * @param array $data
     */
    public function __construct($data) {
        $this->sampleId = $data['sampleId'];
        $this->criteriaId = $data['criteriaId'];
        $this->description = $data['description'];
        $this->note = $data['note'];
    }
}
