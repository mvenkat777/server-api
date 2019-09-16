<?php

namespace Platform\SampleContainer\Commands;

class AddNewSampleCriteriaCommand
{
    /**
     * The id of the sample
     * @var string
     */
    public $sampleId;

    /**
     * The criteria
     * @var string
     */
    public $criteria;

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
        $this->criteria = $data['criteria'];
        $this->description = $data['description'];
        $this->note = $data['note'];
	}
}
