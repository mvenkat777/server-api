<?php 
namespace Platform\HttpLogs\Commands;

class GetLogsByResponseStatusCommand{

	public $responseStatus;

	function __construct($responseStatus)
	{
		$this->responseStatus = $responseStatus;
	}
}