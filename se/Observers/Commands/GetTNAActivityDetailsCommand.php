<?php
namespace Platform\Observers\Commands;

class GetTNAActivityDetailsCommand{

	/**
     * @var tnaId
     */

    public $tnaId;

    function __construct($request)
    {
    	$this->tnaId = $request;
    }
}