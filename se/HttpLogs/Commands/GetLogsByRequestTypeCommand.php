<?php 
namespace Platform\HttpLogs\Commands;

class GetLogsByRequestTypeCommand{

	public $requestType;

	function __construct($requestType)
	{
		$this->requestType = $requestType;
	}
}