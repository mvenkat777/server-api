<?php 
namespace Platform\HttpLogs\Commands;

class GetLogsFromDateToTillDateCommand{

	public $fromDate;

	public $tillDate;

	function __construct($fromDate, $tillDate)
	{
		$this->fromDate = $fromDate;
		$this->tillDate = $tillDate;
	}
}