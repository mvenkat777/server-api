<?php

namespace Platform\SampleContainer\Commands;

class TransformSampleForExportCommand 
{
    /**
     * @var App\Sample
     */
    public $sample;

    /**
     * @param Sample $sample
     */
	public function __construct($sample){
        $this->sample = $sample;
	}

}
