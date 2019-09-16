<?php
namespace Platform\Observers\Commands;

class GetSampleActivityDetailsCommand{

	/**
     * @var sampleId
     */

    public $sampleId;

    function __construct($request)
    {
    	$this->sampleId = $request;
    }
}