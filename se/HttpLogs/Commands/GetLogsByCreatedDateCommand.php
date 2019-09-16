<?php
namespace Platform\HttpLogs\Commands;

class GetLogsByCreatedDateCommand{
	
	public $date; 

	function __construct($date)
	{
		$this->date = $date;
	}
}