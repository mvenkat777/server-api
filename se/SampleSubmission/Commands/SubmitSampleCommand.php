<?php

namespace Platform\SampleSubmission\Commands;

class SubmitSampleCommand
{
    /**
     * Name of the sample submission
     * @var string
     */
    public $name;

    /**
     * Categories in the sample submission
     * @var array
     */
    public $categories;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->categories = $data['categories'];
    }
}
