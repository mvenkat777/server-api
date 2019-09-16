<?php
namespace Platform\Observers\Commands;

class GetTechpackActivityDetailsCommand{

	/**
     * @var tnaId
     */

    public $techpackId;

    function __construct($request)
    {
    	$this->techpackId = $request;
    }
}