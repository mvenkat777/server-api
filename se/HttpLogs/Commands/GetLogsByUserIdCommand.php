<?php 
namespace Platform\HttpLogs\Commands;

class GetLogsByUserIdCommand{

	public $userId;

	public $fromDate;

	function __construct($userId, $fromDate)
	{
		$this->userId = $userId;
		$this->fromDate = $fromDate;
	}
}