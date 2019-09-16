<?php

namespace Platform\SampleContainer\Commands;

class ListSampleContainersCommand
{
    /**
     * The sample containers list filter data
     * @var array
     */
    public $data;

    /**
     * Construct the list command
     * @param array $data
     */
	public function __construct($data){
        $this->data = $data;
	}
}