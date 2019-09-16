<?php

namespace Platform\SampleContainer\Commands;

class AddNewSampleContainerCommand
{
    /**
     * id of the techpack for the new sample container
     * @var string
     */
    public $techpackId;

    /**
     * Contruct the AddNewSampleContainer command
     */
	public function __construct($data){
        $this->techpackId = $data['techpackId'];
	}
}